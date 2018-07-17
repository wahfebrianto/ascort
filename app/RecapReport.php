<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RecapReport extends Model
{
    protected $table = "tax_reports";
    protected $fillable = ['agent_id', 'process_date', 'period', 'month_year', 'nominal',
        'last_nominal_accumulation', 'discounted', 'last_discounted_accumulation',
        'taxable', 'last_taxable_accumulation', 'tax', 'nett_commission', 'data'];

    const START_DAYS    = ['1' =>  1, '2' => 16];
    const END_DAYS      = ['1' => 15, '2' =>  0];
    const SUB_MONTHS    = ['1' =>  0, '2' => -1];

    // ---------------------------------------------- RELATIONSHIP --------------------------------------------------

    public function agent(){
        return $this->belongsTo('App\Agent', 'agent_id');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------

    public function getFormattedProcessDateAttribute()
    {
        return Carbon::parse($this->process_date)->format('d/m/Y');
    }

    public function getAgentNameAttribute()
    {
        return $this->agent()->getResults()['name'];
    }

    public function getPeriodCodeAttribute()
    {
        return Carbon::parse($this->month_year)->format('Ym') . $this->period;
    }

    public static function generatePeriodCode($period, $month, $year) {
        return str_pad($year, 4, '0', STR_PAD_LEFT) .
        str_pad($month, 2, '0', STR_PAD_LEFT) .
        $period;
    }

    public static function getLastNominalAccumulation($agent_id, $month_year){
        $last_report = TaxReport::where('agent_id', '=', $agent_id)
            ->whereRaw("month_year = (select max(month_year) from tax_reports where agent_id = " . $agent_id . " and month_year < '" . $month_year . "' and month_year like '%" . substr($month_year, 0, 4) . "%')")
            ->first();

        $totalAccNow = $last_report == null ? 0 : $last_report->nominal + $last_report->last_nominal_accumulation;

        return $totalAccNow;
    }

    public static function getLastDiscountedAccumulation($agent_id, $month_year){
        $last_report = TaxReport::where('agent_id', '=', $agent_id)
            ->whereRaw("month_year = (select max(month_year) from tax_reports where agent_id = " . $agent_id . " and month_year < '" . $month_year . "' and month_year like '%" . substr($month_year, 0, 4) . "%')")
            ->first();

        $totalAccNow = $last_report == null ? 0 : $last_report->discounted + $last_report->last_discounted_accumulation;

        return $totalAccNow;
    }

    public static function getLastTaxableAccumulation($agent_id, $month_year){
        $last_report = TaxReport::where('agent_id', '=', $agent_id)
            ->whereRaw("month_year = (select max(month_year) from tax_reports where agent_id = " . $agent_id . " and month_year < '" . $month_year . "' and month_year like '%" . substr($month_year, 0, 4) . "%')")
            ->first();

        $totalAccNow = $last_report == null ? 0 : $last_report->taxable + $last_report->last_taxable_accumulation;

        return $totalAccNow;
    }

    public static function deleteLastYearReport()
    {
        $lastYear = TaxReport::where('created_at', '<', Carbon::create(Carbon::now()->year, 1, 1))->get();
        foreach ($lastYear as $report) {
            $report->delete();
        }
    }

    public static function isAllowedToView($period, $month, $year) {
        return Carbon::create() > TaxReport::getDatesOfPeriod($period, $month, $year)['end_date'];
    }

    public static function getDatesOfPeriodCode($period_code) {
        $year = intval(substr($period_code, 0, 4));
        $month = intval(substr($period_code, 4, 2));
        $period = intval(substr($period_code, 6, 1));
        return TaxReport::getDatesOfPeriod($period, $month, $year);
    }

    public static function getDatesOfPeriod($period, $month, $year) {
        $start_date = Carbon::create($year, $month, self::START_DAYS[$period], 0, 0, 0);
        if($period == 2)
            $end_date = Carbon::create($year, $month+1, self::END_DAYS[$period], 23, 59, 59);
        else
            $end_date = Carbon::create($year, $month, self::END_DAYS[$period], 23, 59, 59);
        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
    }

    public static function getPeriodFromDate(Carbon $date) {
        $period = 3;
        for($i=1;$i<=2;$i++) {
            $dates = self::getDatesOfPeriod($i, $date->month, $date->year);
            if($date->between($dates['start_date'], $dates['end_date'])) {
                $period = $i;
                break;
            }
        }
        return $period;
    }

    public static function getPeriodCodeFromDate(Carbon $date) {
        $date_p = $date->copy();
        $period = self::getPeriodFromDate($date_p);
        if($period == 3) {
            $date_p->addMonth(1);
            $period = 1;
        }
        return self::generatePeriodCode($period, $date_p->month, $date_p->year);
    }

    public static function getCurrentPeriodCode() {
        return self::getPeriodCodeFromDate(Carbon::today());
    }

    public static function getNextPeriodCode() {
        $period_code = self::getPeriodCodeFromDate(Carbon::today());
        $year = intval(substr($period_code, 0, 4));
        $month = intval(substr($period_code, 4, 2));
        $period = intval(substr($period_code, 6, 1));
        $current = Carbon::create($year, $month, 1, 0, 0, 0);

        // add period
        if($period == 1) $period++;
        if($period == 2) {
            $current->addMonth(1);
            $period = 1;
        }
        return TaxReport::generatePeriodCode($period, $current->month, $current->year);
    }

    public static function getNextPeriodStartDate() {
        return TaxReport::getDatesOfPeriodCode(TaxReport::getNextPeriodCode())['start_date'];
    }

    public static function isRecalculationAllowed($period, $month, $year) {
        $now = Carbon::now();
        $end_date = self::getDatesOfPeriod($period, $month, $year)['end_date'];
        $end_date->addDays(365);
        return $now < $end_date;
    }

    public static function isSlipCalculated($period, $month, $year) {
        $period_code = TaxReport::generatePeriodCode($period, $month, $year);
        return TaxReport
            ::select(DB::raw('CONCAT(DATE_FORMAT(`month_year`,\'%Y%m\'),`period`) as period_code'))
            ->whereRaw("CONCAT(DATE_FORMAT(`month_year`,'%Y%m'),`period`) = '$period_code'")
            ->count() > 0;
    }

}
