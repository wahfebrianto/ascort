<?php

namespace App\Http\Controllers;

use App\Calculations\Overriding;
use App\Calculations\Tax;
use App\Calculations\TopOverriding;
use App\CommissionReport;
use App\Repositories\AuditRepository as Audit;
use Auth;
use Input;
use App\Calculations\Commission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FinalSummaryController extends Controller
{

    private $minus_session_name = 'finalsummary.minus.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/finalsummary/general.audit-log.category'), trans('slips/finalsummary/general.audit-log.msg-index'));

        $page_title = trans('slips/finalsummary/general.page.index.title');
        $page_description = trans('slips/finalsummary/general.page.index.description');
        $agent_position_lists = \App\AgentPosition::lists('name', 'id')->all();

        $month_lists = [];
        for( $i = 1; $i <= 12; $i++ ) {
            $month_num = str_pad($i, 2, 0, STR_PAD_LEFT);
            $month_name = strftime('%B', mktime(0, 0, 0, $i + 1, 0, 0));
            $month_lists[$month_num] = $month_name;
        }
        $year_lists = array_combine(range(2016, date('Y')), range(2016, date('Y')));

        return view('slips.finalsummary.index', compact('agent_position_lists', 'month_lists', 'year_lists', 'page_title', 'page_description'));
    }

    public function export()
    {
        set_time_limit(60);
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/finalsummary/general.audit-log.category'), trans('slips/finalsummary/general.audit-log.msg-export'));

        $period = Input::get('period');
        $month = Input::get('month');
        $year = Input::get('year');
        $recalc = false;

        // ---------------------------------------------- COMMISSION -------------------------------------------------
        $builder = \App\Agent
            ::with('sales')
            ->with('agent_position')
            ->where('is_active', '=', 1);

        $isCalculatedCommission = CommissionReport::isSlipCalculated($period, $month, $year, Commission::TYPE);

        if(null != Input::get('agent_position_id') && Input::get('agent_position_id') != 'all') {
            $builder->where('agent_position_id', '=', Input::get('agent_position_id'));
        }
        if(null != Input::get('agent_id') && Input::get('agent_id') != 'all') {
            $builder->where('id', '=', Input::get('agent_id'));
        }
        $agents = $builder->get();

        if(\Input::has('recalc')) {
            if(!CommissionReport::isRecalculationAllowed($period, $month, $year) && $isCalculatedCommission) {
                \Flash::error("Recalculation for commission report for period $period, $month $year is not allowed.");
                return redirect(route('slips.finalsummary.index'));
            }
            $recalc = true;
        }


        // ---------------------------------------------- OVERRIDING -------------------------------------------------
        $isCalculatedOverriding = CommissionReport::isSlipCalculated($period, $month, $year, Overriding::TYPE);

        if(\Input::has('recalc')) {
            if(!CommissionReport::isRecalculationAllowed($period, $month, $year) && $isCalculatedOverriding) {
                \Flash::error("Recalculation for overriding report for period $period, $month $year is not allowed.");
                return redirect(route('slips.finalsummary.index'));
            }
            $recalc = true;
        }


        // ---------------------------------------------- RECFEE -------------------------------------------------
        $isCalculatedRecFee = CommissionReport::isSlipCalculated($period, $month, $year, TopOverriding::TYPE);

        if(\Input::has('recalc')) {
            if(!CommissionReport::isRecalculationAllowed($period, $month, $year) && $isCalculatedRecFee) {
                \Flash::error("Recalculation for commission report for period $period, $month $year is not allowed.");
                return redirect(route('slips.topoverridingsummary.index'));
            }
            $recalc = true;
        }


        // -------------------------------------------------------------------------------------------------------

        $agentFinalCommissions = [];
        foreach($agents as $agent) {
            $com = new Commission($agent, $period, $month, $year);
            $com->calculate($recalc);
            $ovr = new Overriding($agent, $period, $month, $year);
            $ovr->calculate($recalc);
            $rec = new TopOverriding($agent, $period, $month, $year);
            $rec->calculate($recalc);

            $com->total_commission += $ovr->total_commission + $rec->total_commission;
            $com->gross_commission += $ovr->gross_commission + $rec->gross_commission;

            $last_YTD = $com->last_YTD + $ovr->last_YTD + $rec->last_YTD;
            $current_YTD = $com->gross_commission;

            $new_tax = new Tax($agent, $period, $month, $year);
            $new_tax->calculate($recalc);
            $com->tax = $new_tax->tax;

            $com->nett_commission = $current_YTD - $com->tax;

            if ($com->total_commission != 0)
                $agentFinalCommissions[] = $com;
        }

        $html = \View::make('pdf.slips.finalsummary', compact('agents', 'agentFinalCommissions', 'period', 'month', 'year'))->render();
        // $html = str_replace('id=', 'class=', $html); // DOMPDF workaround -> https://github.com/barryvdh/laravel-dompdf/issues/96
        return $html;
    }
}
