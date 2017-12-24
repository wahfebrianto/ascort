<?php

namespace App\Http\Controllers;

use App\CommissionReport;
use Illuminate\Http\Request;
use App\Repositories\AuditRepository as Audit;
use Auth;
use Input;
use App\Calculations\Commission;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommissionSlipsSummaryController extends Controller
{

    private $minus_session_name = 'commissionsummary.minus.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/commission/general.audit-log.category'), trans('slips/commission/general.audit-log.msg-index'));

        $page_title = trans('slips/commissionsummary/general.page.index.title');
        $page_description = trans('slips/commissionsummary/general.page.index.description');
        $agent_position_lists = \App\AgentPosition::lists('name', 'id')->all();

        $month_lists = [];
        for( $i = 1; $i <= 12; $i++ ) {
            $month_num = str_pad($i, 2, 0, STR_PAD_LEFT);
            $month_name = strftime('%B', mktime(0, 0, 0, $i + 1, 0, 0));
            $month_lists[$month_num] = $month_name;
        }
        $year_lists = array_combine(range(2016, date('Y')), range(2016, date('Y')));

        return view('slips.commissionsummary.index', compact('agent_position_lists', 'month_lists', 'year_lists', 'page_title', 'page_description'));
    }

    public function export()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/commission/general.audit-log.category'), trans('slips/commission/general.audit-log.msg-export'));

        $builder = \App\Agent
            ::with('sales')
            ->with('agent_position')
            ->where('is_active', '=', 1);

        $period = Input::get('period');
        $month = Input::get('month');
        $year = Input::get('year');
        $isCalculated = CommissionReport::isSlipCalculated($period, $month, $year, Commission::TYPE);
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
                return redirect(route('slips.commissionsummary.index'));
            }
            $recalc = true;
        }

        $commissions = [];
        foreach($agents as $agent) {
            $commissions[$agent->id] = new Commission($agent, $period, $month, $year);
            $commissions[$agent->id]->calculate($recalc);
        }

        $html = \View::make('pdf.slips.commissionsummary', compact('agents', 'commissions', 'period', 'month', 'year'))->render();
        // $html = str_replace('id=', 'class=', $html); // DOMPDF workaround -> https://github.com/barryvdh/laravel-dompdf/issues/96
        return $html;
    }
}
