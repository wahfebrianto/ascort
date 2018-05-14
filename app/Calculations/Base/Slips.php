<?php
/**
 * Created by PhpStorm.
 * User: harto
 * Date: 3/17/2016
 * Time: 1:00 PM
 */

namespace App\Calculations\Base;

use App\CommissionReport;
use Carbon\Carbon;
use DB;

abstract class Slips
{
    const TYPE = "SLIPS";

    public $commission_hold;
    public $minus;
    public $cuts;
    public $tax_adjustment;
    public $last_YTD;
    public $total_nominal;
    public $total_FYP;
    public $total_commission;
    public $gross_commission;
    public $tax;
    public $nett_commission;
    public $process_date;
    public $additional;

    public $agent;

    public $sales;

    public $period;
    public $month;
    public $year;

    public $start_date;
    public $end_date;

    public $data; // for save in db (json)

    public function __construct()
    {
        $this->sales = [];
        $this->commission_hold = 0;
        $this->minus = 0;
        $this->cuts = 0;
        $this->additional = 0;
        $this->tax_adjustment = 0;
        $this->total_nominal = 0;
        $this->total_FYP = 0;
        $this->total_commission = 0;
        $this->gross_commission = 0;
        $this->tax = 0;
        $this->nett_commission = 0;
        $this->process_date = Carbon::now();
    }

    public function rounding() {
        $this->tax_adjustment   = round($this->tax_adjustment);
        $this->last_YTD         = round($this->last_YTD);
        $this->total_nominal    = round($this->total_nominal);
        $this->total_FYP        = round($this->total_FYP);
        $this->total_commission = round($this->total_commission);
        $this->gross_commission = round($this->gross_commission);
        $this->tax              = round($this->tax);
        $this->nett_commission  = round($this->nett_commission);
    }

    public function save()
    {
        $month_year = Carbon::create($this->year, $this->month, CommissionReport::START_DAYS[$this->period . ''], 0, 0, 0)->format('Y-m-d');
        if($this->isSaved()) {
            CommissionReport::where([
                'agent_id' => $this->agent->id,
                'type' => $this::TYPE,
                'period' => $this->period,
                'month_year' => $month_year
            ])->delete();
        }

        $this->last_YTD = CommissionReport::getLastYTD($this->agent->id, $this::TYPE, $month_year);
        $comm_report = CommissionReport::create([
            'agent_id' => $this->agent->id,
            'type' => $this::TYPE,
            'process_date' => $this->process_date,
            'period' => $this->period,
            'month_year' => $month_year,
            'total_FYP' => $this->total_FYP,
            'total_commission' => $this->total_commission,
            'commission_hold' => $this->commission_hold,
            'minus' => $this->minus,
            'cuts' => $this->cuts,
            'additional' => $this->additional,
            'tax_adjustment' => $this->tax_adjustment,
            'current_YTD' => $this->gross_commission,
            'last_YTD' => $this->last_YTD,
            'total_nominal' => $this->total_nominal,
            'gross_commission' => $this->gross_commission,
            'tax' => $this->tax,
            'nett_commission' => $this->nett_commission,
            'data' => json_encode($this->data)
        ]);
        $comm_report->save();
    }

    public function load() {
        $month_year = Carbon::create($this->year, $this->month, CommissionReport::START_DAYS[$this->period . ''], 0, 0, 0)->format('Y-m-d');
        $comm_report = CommissionReport
            ::where([
                'agent_id' => $this->agent->id,
                'type' => $this::TYPE,
                'period' => $this->period,
                'month_year' => $month_year
            ])->get()->first();
        $this->commission_hold      = $comm_report->commission_hold;
        $this->minus                = $comm_report->minus;
        $this->cuts                 = $comm_report->cuts;
        $this->additional           = $comm_report->additional;
        $this->last_YTD             = $comm_report->last_YTD;
        $this->tax_adjustment       = $comm_report->tax_adjustment;
        $this->total_FYP            = $comm_report->total_FYP;
        $this->total_nominal        = $comm_report->total_nominal;
        $this->total_commission     = $comm_report->total_commission;
        $this->gross_commission     = $comm_report->gross_commission;
        $this->tax                  = $comm_report->tax;
        $this->nett_commission      = $comm_report->nett_commission;
        $this->process_date         = Carbon::parse($comm_report->process_date);
        $this->loadData(json_decode($comm_report->data));
    }

    abstract public function loadData($data);

    public function isSaved()
    {
        $period_code = CommissionReport::generatePeriodCode($this->period, $this->month, $this->year);
        return CommissionReport
            ::select(DB::raw('CONCAT(DATE_FORMAT(`month_year`,\'%Y%m\'),`period`) as period_code'))
            ->where('type', '=', $this::TYPE)
            ->where('agent_id', '=', $this->agent->id)
            ->whereRaw("CONCAT(DATE_FORMAT(`month_year`,'%Y%m'),`period`) = '$period_code'")
            ->count() > 0;
    }


    public static function calculateTax($agent, $lastYTD, $profitNow, $level)
    {
      $percentage = [2.5, 7.5, 12.5, 15];
      $bound = [0, 50000000, 250000000, 500000000];
      $percentage = ($agent->NPWP == 0)?$percentage[$level]*120/100:$percentage[$level];
      $bound = ($level == 3)?$lastYTD + $profitNow:$bound[$level+1]-$bound[$level];
      $dif_last = $lastYTD - $bound;
      if($dif_last > 0)
      {
        return self::calculateTax($agent, $dif_last, $profitNow, $level + 1);
      }
      else {
        if(abs($dif_last) >= $profitNow)
        {
          return $profitNow * $percentage / 100;
        }
        else
        {
          $dif_now = $profitNow - abs($dif_last);
          return abs($dif_last) * $percentage / 100 + self::calculateTax($agent, 0, $dif_now, $level + 1);
        }
      }

    public static function getLastYTDByAgent($agent,$date,$minus_session_name,$type){
        $year = explode('/',$date)[2];
        $start_date = '01/01/'.$year;
        $end_date = $date;
        if($type=="OR")
            $slips = new \App\Calculations\Overriding($agent, $start_date , $end_date);
        elseif($type == "Commission")
            $slips = new \App\Calculations\Commission($agent,$start_date,$end_date);
        else if($type == "TopOverriding")
            $slips = new \App\Calculations\TopOverriding($agent,$start_date,$end_date);
        if (\Session::has($minus_session_name . $agent->id)) {
            $slips->minus = \Session::get($minus_session_name . $agent->id)['value'];
            \Session::forget($minus_session_name . $agent->id);
        }
        $slips->calculate(true);
        return $slips->gross_commission;

    }
}
