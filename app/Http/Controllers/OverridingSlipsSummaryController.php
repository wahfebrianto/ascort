<?php

namespace App\Http\Controllers;

use App\Calculations\Overriding;
use App\CommissionReport;
use Illuminate\Http\Request;
use App\Repositories\AuditRepository as Audit;
use Auth;
use Input;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OverridingSlipsSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/overridingsummary/general.audit-log.category'), trans('slips/overridingsummary/general.audit-log.msg-index'));

        $page_title = trans('slips/overridingsummary/general.page.index.title');
        $page_description = trans('slips/overridingsummary/general.page.index.description');
        $agent_position_lists = \App\AgentPosition::withoutLowest()->lists('name', 'id')->all();
        $agent_lists = ['all' => 'All'] + \App\Agent::isActive()->whereIn('agent_position_id', array_keys($agent_position_lists))->lists('name', 'id')->all();

        $month_lists = [];
        for( $i = 1; $i <= 12; $i++ ) {
            $month_num = str_pad($i, 2, 0, STR_PAD_LEFT);
            $month_name = strftime('%B', mktime(0, 0, 0, $i + 1, 0, 0));
            $month_lists[$month_num] = $month_name;
        }
        $year_lists = array_combine(range(2016, date('Y')), range(2016, date('Y')));

        return view('slips.overridingsummary.index', compact('agent_position_lists', 'agent_lists', 'month_lists', 'year_lists', 'page_title', 'page_description'));
    }

    public function export()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/overridingsummary/general.audit-log.category'), trans('slips/overridingsummary/general.audit-log.msg-export'));
        $agent_position_lists = \App\AgentPosition::withoutLowest()->lists('name', 'id')->all();
        $builder = \App\Agent
            ::with('childrenRecursive')
            ->with('sales')
            ->with('parent')
            ->where('is_active', '=', 1)
            ->whereIn('agent_position_id', array_keys($agent_position_lists));

        $period = Input::get('period');
        $month = Input::get('month');
        $year = Input::get('year');
        $isCalculated = CommissionReport::isSlipCalculated($period, $month, $year, Overriding::TYPE);
        $recalc = false;

        if(null != Input::get('agent_position_id') && Input::get('agent_position_id') != 'all') {
            $builder->where('agent_position_id', '=', Input::get('agent_position_id'));
        }
        if(null != Input::get('agent_id') && Input::get('agent_id') != 'all') {
            $builder->where('id', '=', Input::get('agent_id'));
        }

        $agents = $builder->get();

        if(\Input::has('recalc')) {
            if(!CommissionReport::isRecalculationAllowed($period, $month, $year) && $isCalculated) {
                \Flash::error("Recalculation for commission report for period $period, $month $year is not allowed.");
                return redirect(route('slips.overridingsummary.index'));
            }
            $recalc = true;
        }

        $ovrs = [];
        foreach($agents as $agent) {
            $ovr = new Overriding($agent, $period, $month, $year);
            $ovr->calculate($recalc);
            $ovrs[] = $ovr;
        }

        $html = \View::make('pdf.slips.overridingsummary', compact('ovrs', 'period', 'month', 'year'))->render();
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
