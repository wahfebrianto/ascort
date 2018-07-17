<?php
/**
 * Created by PhpStorm.
 * User: harto
 * Date: 3/16/2016
 * Time: 11:47 AM
 */

namespace App\Calculations;


use App\Agent;
use App\CommissionReport;
use App\RecapReport;
use Carbon\Carbon;

class Recap
{
    public $agent;
    public $process_date;
    public $period;
    public $month;
    public $year;

    public $nominal;
    public $last_nominal_accumulation;
    public $discounted;
    public $last_discounted_accumulation;
    public $taxable;
    public $last_taxable_accumulation;
    public $tax;
    public $nett_commission;

    public $data; // for save in db (json)

    public function __construct(Agent $agent, $period, $month, $year)
    {
        $this->agent = $agent;
        $this->process_date = Carbon::now();
        $this->period = $period;
        $this->month = $month;
        $this->year = $year;
        $this->nominal = 0;
        $this->last_nominal_accumulation = 0;
        $this->discounted = 0;
        $this->last_discounted_accumulation = 0;
        $this->taxable = 0;
        $this->last_taxable_accumulation = 0;
        $this->tax = 0;
        $this->nett_commission = 0;
    }

    public function save()
    {
        $month_year = Carbon::create($this->year, $this->month, TaxReport::START_DAYS[$this->period . ''], 0, 0, 0)->format('Y-m-d');
        if($this->isSaved()) {
            TaxReport::where([
                'agent_id' => $this->agent->id,
                'period' => $this->period,
                'month_year' => $month_year
            ])->delete();
        }

        $comm_report = TaxReport::create([
            'agent_id' => $this->agent->id,
            'process_date' => $this->process_date,
            'period' => $this->period,
            'month_year' => $month_year,
            'nominal' => $this->nominal,
            'last_nominal_accumulation' => $this->last_nominal_accumulation,
            'discounted' => $this->discounted,
            'last_discounted_accumulation' => $this->last_discounted_accumulation,
            'taxable' => $this->taxable,
            'last_taxable_accumulation' => $this->last_taxable_accumulation,
            'tax' => $this->tax,
            'nett_commission' => $this->nett_commission,
            'data' => json_encode($this->data)
        ]);
        $comm_report->save();
    }

    public function load() {
        $month_year = Carbon::create($this->year, $this->month, TaxReport::START_DAYS[$this->period . ''], 0, 0, 0)->format('Y-m-d');
        $comm_report = TaxReport
            ::where([
                'agent_id' => $this->agent->id,
                'period' => $this->period,
                'month_year' => $month_year
            ])->get()->first();
        $this->process_date                 = Carbon::parse($comm_report->process_date);
        $this->nominal                      = $comm_report->nominal;
        $this->last_nominal_accumulation    = $comm_report->last_nominal_accumulation;
        $this->discounted                   = $comm_report->discounted;
        $this->last_discounted_accumulation = $comm_report->last_discounted_accumulation;
        $this->taxable                      = $comm_report->taxable;
        $this->last_taxable_accumulation    = $comm_report->last_taxable_accumulation;
        $this->tax                          = $comm_report->tax;
        $this->nett_commission              = $comm_report->nett_commission;
        $this->loadData(json_decode($comm_report->data));
    }

    public function loadData($data)
    {
        if(array_key_exists('agent', $data))
            $this->agent = $data->agent;
    }

    public function isSaved()
    {
        $period_code = TaxReport::generatePeriodCode($this->period, $this->month, $this->year);
        return TaxReport
            ::select(\DB::raw('CONCAT(DATE_FORMAT(`month_year`,\'%Y%m\'),`period`) as period_code'))
            ->where('agent_id', '=', $this->agent->id)
            ->whereRaw("CONCAT(DATE_FORMAT(`month_year`,'%Y%m'),`period`) = '$period_code'")
            ->count() > 0;
    }

    public function calculate($force_calculate = false)
    {
        if($this->isSaved() && !$force_calculate) {
            $this->load();
        } else {
            $month_year = Carbon::create($this->year, $this->month, TaxReport::START_DAYS[$this->period . ''], 0, 0, 0)->format('Y-m-d');

            $comm_report = CommissionReport::where(['month_year' => $month_year, "agent_id" => $this->agent->id])
                ->groupBy(['month_year', 'agent_id'])
                ->addSelect(\DB::raw('SUM(current_YTD) as total_current_YTD'))->first();
            $this->nominal = $comm_report == null? 0 : $comm_report->total_current_YTD;

            $this->last_nominal_accumulation = TaxReport::getLastNominalAccumulation($this->agent->id, $month_year);
            $this->last_discounted_accumulation = TaxReport::getLastDiscountedAccumulation($this->agent->id, $month_year);
            $this->last_taxable_accumulation = TaxReport::getLastTaxableAccumulation($this->agent->id, $month_year);

            $this->discounted = ($this->nominal + $this->last_nominal_accumulation) * 0.5;
            $this->taxable = $this->discounted - $this->last_taxable_accumulation;

            //$this->tax = $this->calculateTax($this->last_taxable_accumulation, $this->taxable, $this->agent->NPWP);
            $this->tax = $this->calculateTaxWithoutAccumulation($this->nominal, $this->agent->NPWP);

            $this->data = ['agent' => $this->agent];
            $this->nett_commission = $this->nominal - $this->tax;
            $this->save();
        }
    }

    public function calculateTax($last_taxable, $taxable, $NPWP){
        $tax = Tax::taxRecursive($last_taxable, $taxable, 0, 0, key(config('tax_percentage')[0]) )[3];
        if ($NPWP == "0") $tax *= 1.2; //if agent doesn't have NPWP, add 20%

        return $tax;
    }

    public function calculateTaxWithoutAccumulation($nominal, $NPWP){
        $tax = Tax::taxRecursive(0, $nominal, 0, 0, key(config('tax_percentage')[0]) )[3];
        $tax *= 0.5; // discount 50%
        if ($NPWP == "0") $tax *= 1.2; //if agent doesn't have NPWP, add 20%

        return $tax;
    }

    /*
    public function getTax($month_year, $agent_filter)
    {

        $builder = CommissionReport
            ::where([
                'month_year' => $month_year
            ])->with('agent')
            ->groupBy(['month_year', 'agent_id'])
            ->addSelect(\DB::raw('SUM(last_YTD) as total_last_YTD'))
            ->addSelect(\DB::raw('SUM(current_YTD) as total_current_YTD'))
            ->addSelect('commission_reports.*');

        if(null != $agent_filter && $agent_filter != 'all') {
            $builder->where('id', '=', $agent_filter);
        }

        $reports = $builder->get();

        $data = [];
        foreach ($reports as $report){
            if($report->total_current_YTD != 0){
                $new_row = new \stdClass();
                $new_row->agent_name = $report->agent_name;
                $new_row->agent_position_name = $report->agent->agent_position_name;
                $new_row->last_YTD = $report->total_last_YTD;
                $new_row->current_YTD = $report->total_current_YTD;
                // discount 50% $report->total_current_YTD * 0.5
                $new_row->tax = Tax::taxRecursive($report->total_last_YTD, $report->total_current_YTD * 0.5, 0, 0, key(config('tax_percentage')[0]) )[3];

                //$new_row->tax *= 0.5; //discount 50%
                if ($report->agent->NPWP == "0") $new_row->tax *= 1.2; //if agent doesn't have NPWP, add 20%

                $new_row->nett_commission = $new_row->current_YTD - $new_row->tax;

                $data[] = $new_row;
            }
        }

        return $data;
    }

    public function getTaxForOneAgent($last_YTD, $current_YTD, $NPWP){
        $current_YTD *= 0.5; // discount 50%
        $tax = Tax::taxRecursive($last_YTD, $current_YTD, 0, 0, key(config('tax_percentage')[0]) )[3];
        // $tax *= 0.5; //discount 50%
        if ($NPWP == "0") $tax *= 1.2; //if agent doesn't have NPWP, add 20%

        return $tax;
    }
    */

    /*
     * Returns :
     *      [0] => last_taxable
     *      [1] => taxable
     *      [2] => categoryNumber
     *      [3] => tax amount
     */
    private static function taxRecursive($last_taxable, $taxable, $categoryNumber, $tax, $sisa_threshold){
        $threshold = key(config('tax_percentage')[$categoryNumber]);
        $percentage = config('tax_percentage')[$categoryNumber][$threshold];

        if($sisa_threshold == 0) $sisa_threshold = $threshold;

        if($last_taxable - $sisa_threshold > 0){
            // LAST TAXABLE PERCENTAGE CALCULATION
            if($categoryNumber < count(config('tax_percentage'))-1){
                $last_taxable -= $sisa_threshold;
                $categoryNumber++;
                $sisa_threshold = 0;

                $ret = Tax::taxRecursive($last_taxable, $taxable, $categoryNumber, $tax, $sisa_threshold);
                $last_taxable = $ret[0];
                $taxable = $ret[1];
                $categoryNumber = $ret[2];
                $tax = $ret[3];
            }else{
                //maximum
                $sisa_threshold = 0;
                $last_taxable = 0;
            }

        }else{
            // LAST TAXABLE CALCULATION DONE
            $sisa_threshold = $sisa_threshold - $last_taxable;
            $last_taxable = 0;

            if($taxable - $sisa_threshold > 0){
                if($categoryNumber < count(config('tax_percentage'))-1){
                    $tax += $sisa_threshold * ($percentage/100);
                    $taxable -= $sisa_threshold;
                    $categoryNumber++;
                    $sisa_threshold = 0;

                    $ret = Tax::taxRecursive($last_taxable, $taxable, $categoryNumber, $tax, $sisa_threshold);
                    $last_taxable = $ret[0];
                    $taxable = $ret[1];
                    $categoryNumber = $ret[2];
                    $tax = $ret[3];
                }else{
                    //maximum
                    $tax += $taxable * ($percentage/100);
                    $taxable = 0;
                }

            }else{
                $tax += $taxable * ($percentage/100);
                $taxable = 0;
            }

        }

        return [$last_taxable, $taxable, $categoryNumber, $tax, $sisa_threshold];
    }
}
