<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AuditRepository as Audit;
use Auth;
use Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Calculations\TopOverriding;
use App\CommissionReport;

class TopOverridingSlipsSummaryController extends Controller
{

    private $minus_session_name = 'topovr.minus.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/topoverridingsummary/general.audit-log.category'), trans('slips/topoverridingsummary/general.audit-log.msg-index'));

        $page_title = trans('slips/topoverridingsummary/general.page.index.title');
        $page_description = trans('slips/topoverridingsummary/general.page.index.description');

        $agent_lists = ['all' => 'All'] + \App\Agent::isActive()->where('agent_position_id', '=', \App\AgentPosition::getHighestAgentPosition()->id)->lists('name', 'id')->all();

        $month_lists = [];
        for( $i = 1; $i <= 12; $i++ ) {
            $month_num = str_pad($i, 2, 0, STR_PAD_LEFT);
            $month_name = strftime('%B', mktime(0, 0, 0, $i + 1, 0, 0));
            $month_lists[$month_num] = $month_name;
        }
        $year_lists = array_combine(range(2016, date('Y')), range(2016, date('Y')));

        return view('slips.topoverridingsummary.index', compact('agent_lists', 'month_lists', 'year_lists', 'page_title', 'page_description'));
    }

    public function export()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/topoverridingsummary/general.audit-log.category'), trans('slips/topoverridingsummary/general.audit-log.msg-export'));

        $builder = \App\Agent
            ::with('childrenRecursive')
            ->with('sales')
            ->with('parent')
            ->where('is_active', '=', 1)
            ->where('agent_position_id', '=', \App\AgentPosition::getHighestAgentPosition()->id);

        $period = Input::get('period');
        $month = Input::get('month');
        $year = Input::get('year');
        $isCalculated = CommissionReport::isSlipCalculated($period, $month, $year, TopOverriding::TYPE);
        $recalc = false;
        if(null != Input::get('agent_id') && Input::get('agent_id') != 'all') {
            $builder->where('id', '=', Input::get('agent_id'));
        }

        $agents = $builder->get();

        if(\Input::has('recalc')) {
            if(!CommissionReport::isRecalculationAllowed($period, $month, $year) && $isCalculated) {
                \Flash::error("Recalculation for commission report for period $period, $month $year is not allowed.");
                return redirect(route('slips.topoverridingsummary.index'));
            }
            $recalc = true;
        }

        $ovrs = [];
        foreach($agents as $agent) {
            $ovr = new TopOverriding($agent, $period, $month, $year);
            $ovr->calculate($recalc);
            $ovrs[] = $ovr;
        }

        $html = \View::make('pdf.slips.topoverridingsummary', compact('ovrs', 'period', 'month', 'year'))->render();
        $html = str_replace('id=', 'class=', $html); // DOMPDF workaround -> https://github.com/barryvdh/laravel-dompdf/issues/96
        /*
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('agents.pdf');
        */
        return $html;

        // $pdf = \App::make('snappy.pdf.wrapper');
        // $pdf->loadHtml($html);
        // $pdf->setPaper('A4');
        // $pdf->setOrientation('landscape');
        // return $pdf->inline();
    }
}
