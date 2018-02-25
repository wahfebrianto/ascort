<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class CommissionReport extends Model
{
    protected $table = "commission_reports";
    protected $fillable = ['agent_id', 'type', 'process_date', 'period', 'month_year', 'commission_hold',
        'minus', 'cuts', 'tax_adjustment', 'current_YTD', 'last_YTD', 'total_nominal', 'gross_commission',
        'total_FYP', 'total_commission', 'tax', 'nett_commission', 'data'];

    const START_DAYS    = ['1' =>  1, '2' =>  8, '3' => 15, '4' => 22];
    const END_DAYS      = ['1' =>  7, '2' => 14, '3' => 21, '4' =>  0];
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

    public static function getCommissionReportFromId($id)
    {
        $found = CommissionReport::where('id', '=', $id)->where('is_active', '=', 1)->get();
        return (count($found)>0 ? $found[0] : null);
    }

    public static function generatePeriodCode($period, $month, $year) {
        return str_pad($year, 4, '0', STR_PAD_LEFT) .
            str_pad($month, 2, '0', STR_PAD_LEFT) .
            $period;
    }

    public static function getLastYTD($agent_id, $type, $month_year){
        $last_report = CommissionReport::where('agent_id', '=', $agent_id)->where('type', '=', $type)
            ->whereRaw("month_year = (select max(month_year) from commission_reports where agent_id = " . $agent_id . " and month_year < '" . $month_year . "' and month_year like '%" . substr($month_year, 0, 4) . "%')")
            ->first();

        $totalYTDNow = $last_report == null ? 0 : $last_report->current_YTD + $last_report->last_YTD;

        return $totalYTDNow;
    }

    public static function deleteLastYearReport()
    {
        $lastYear = CommissionReport::where('created_at', '<', Carbon::create(Carbon::now()->year, 1, 1))->get();
        foreach ($lastYear as $report) {
            $report->delete();
        }
    }

    public static function getMinimumAllowedViewDate($period, $month, $year) {
        return CommissionReport::getDatesOfPeriod($period, $month, $year)['end_date'];
    }

    public static function isAllowedToView($period, $month, $year) {
        return Carbon::create() > CommissionReport::getDatesOfPeriod($period, $month, $year)['end_date'];
    }

    public static function getDatesOfPeriodCode($period_code) {
        $year = intval(substr($period_code, 0, 4));
        $month = intval(substr($period_code, 4, 2));
        $period = intval(substr($period_code, 6, 1));
        return CommissionReport::getDatesOfPeriod($period, $month, $year);
    }

    public static function getDatesOfPeriod($period, $month, $year) {
        $start_date = Carbon::create($year, $month, self::START_DAYS[$period], 0, 0, 0);
		if($period == 4)
			$end_date = Carbon::create($year, $month+1, self::END_DAYS[$period], 23, 59, 59);
		else
			$end_date = Carbon::create($year, $month, self::END_DAYS[$period], 23, 59, 59);
        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
    }

    public static function getPeriodFromDate(Carbon $date) {
        $period = 5;
        for($i=1;$i<=4;$i++) {
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
        if($period == 5) {
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
        if($period < 4) $period++;
        if($period == 4) {
            $current->addMonth(1);
            $period = 1;
        }
        return CommissionReport::generatePeriodCode($period, $current->month, $current->year);
    }

    public static function getNextPeriodStartDate() {
        return CommissionReport::getDatesOfPeriodCode(CommissionReport::getNextPeriodCode())['start_date'];
    }

    public static function isRecalculationAllowed($period, $month, $year) {
        $now = Carbon::now();
        $end_date = self::getDatesOfPeriod($period, $month, $year)['end_date'];
        $end_date->addDays(365);
        return $now < $end_date;
    }

    public static function isSlipCalculated($period, $month, $year, $type) {
        $period_code = CommissionReport::generatePeriodCode($period, $month, $year);
        return CommissionReport
            ::select(DB::raw('CONCAT(DATE_FORMAT(`month_year`,\'%Y%m\'),`period`) as period_code'))
            ->where('type', '=', $type)
            ->whereRaw("CONCAT(DATE_FORMAT(`month_year`,'%Y%m'),`period`) = '$period_code'")
            ->count() > 0;
    }

}
