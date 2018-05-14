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

class TopOverridingSlipsController extends Controller
{

    private $minus_session_name = 'topovr.minus.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/topoverriding/general.audit-log.category'), trans('slips/topoverriding/general.audit-log.msg-index'));

        $page_title = trans('slips/topoverriding/general.page.index.title');
        $page_description = trans('slips/topoverriding/general.page.index.description');

        $agent_lists = ['all' => 'All'] + \App\Agent::isActive()->where('agent_position_id', '=', \App\AgentPosition::getHighestAgentPosition()->id)->lists('name', 'id')->all();

        $month_lists = [];
        for( $i = 1; $i <= 12; $i++ ) {
            $month_num = str_pad($i, 2, 0, STR_PAD_LEFT);
            $month_name = strftime('%B', mktime(0, 0, 0, $i + 1, 0, 0));
            $month_lists[$month_num] = $month_name;
        }
        $year_lists = array_combine(range(2016, date('Y')), range(2016, date('Y')));

        return view('slips.topoverriding.index', compact('agent_lists', 'month_lists', 'year_lists', 'page_title', 'page_description'));
    }

    public function minus(Request $request)
    {
        $period = Input::get('period');
        $month = Input::get('month');
        $year = Input::get('year');
        $isCalculated = CommissionReport::isSlipCalculated($period, $month, $year, TopOverriding::TYPE);
        if(!CommissionReport::isAllowedToView($period, $month, $year)) {
            \Flash::error("topoverriding report for period $period, $month $year is not available.");
            return redirect()->back();
        }
        if(!$request->has('recalc') && $isCalculated) {
            return redirect(route('slips.topoverriding.export') . '?' . $_SERVER['QUERY_STRING']);
        } else {
            if(!CommissionReport::isRecalculationAllowed($period, $month, $year) && $isCalculated) {
                \Flash::error("Recalculation for commission report for period $period, $month $year is not allowed.");
                return redirect(route('slips.topoverriding.index'));
            }
        }

        $page_title = trans('slips/topoverriding/general.page.index.title');
        $page_description = trans('slips/topoverriding/general.page.index.description');

        if($request->has('minus_agent_id') && $request->has('minus_value')) {
            $agent = \App\Agent::where('id', '=', $request->get('minus_agent_id'))->first();
            if(null != $agent) {
                $key = $this->minus_session_name . $request->get('minus_agent_id');

                if($request->session()->has($key)) $request->session()->forget($key);
                $request->session()->put($key, ['agent' => $agent, 'value' => $request->get('minus_value')]);

                $url = \URL::previous();
                $url = preg_replace('/(?:&|(\?))' . 'minus_agent_id' . '=[^&]*(?(1)&|)?/i', "$1", $url);
                $url = preg_replace('/(?:&|(\?))' . 'minus_value' . '=[^&]*(?(1)&|)?/i', "$1", $url);
                return redirect($url);
            }
        }

        $builder = \App\Agent
            ::where('is_active', '=', 1)
            ->where('agent_position_id', '=', \App\AgentPosition::getHighestAgentPosition()->id);
        if(null != Input::get('agent_id') && Input::get('agent_id') != 'all') {
            $builder->where('id', '=', Input::get('agent_id'));
        }
        if(null != Input::get('dist_channel')) {
            $builder->where('dist_channel', 'like', '%' . Input::get('dist_channel') . '%');
        }
        $agent_lists = $builder->lists('name', 'id');

        return view('slips.topoverriding.minus', compact('agent_lists', 'page_title', 'page_description'));
    }

    public function delete_minus() {
        $url = \URL::previous();
        $url = preg_replace('/(?:&|(\?))' . 'minus_agent_id' . '=[^&]*(?(1)&|)?/i', "$1", $url);
        $url = preg_replace('/(?:&|(\?))' . 'minus_value' . '=[^&]*(?(1)&|)?/i', "$1", $url);
        if(Input::has('key')) {
            $key = $this->minus_session_name . Input::get('key');
            if (\Session::has($key)) \Session::forget($key);
            \Flash::success('Selected agent\'s minus deleted.');
        }
        return redirect($url);
    }

    public function export()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/topoverriding/general.audit-log.category'), trans('slips/topoverriding/general.audit-log.msg-export'));

        $builder = \App\Agent
            ::with('childrenRecursive')
            ->with('sales')
            ->with('parent')
            ->where('is_active', '=', 1)
            ->where('agent_position_id', '=', \App\AgentPosition::getHighestAgentPosition()->id);

        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        if($start_date == "" or $end_date == ""){
            \Flash::error("Rec Fee report for period $start_date until $end_date is not available");
            return redirect()->back();
        }


        // $isCalculated = CommissionReport::isSlipCalculated($period, $month, $year, TopOverriding::TYPE);
        $recalc = false;

        if(null != Input::get('agent_id') && Input::get('agent_id') != 'all') {
            $builder->where('id', '=', Input::get('agent_id'));
        }
        if(null != Input::get('dist_channel')) {
            $builder->where('dist_channel', 'like', '%' . Input::get('dist_channel') . '%');
        }

        $agents = $builder->get();

        // if(\Input::has('recalc')) {
        //     if(!CommissionReport::isRecalculationAllowed($period, $month, $year) && $isCalculated) {
        //         \Flash::error("Recalculation for commission report for period $period, $month $year is not allowed.");
        //         return redirect(route('slips.topoverriding.index'));
        //     }
        //     $recalc = true;
        // }

        $ovrs = [];
        $allsalecom = 0;
        foreach($agents as $agent) {
            $endoflastytd =Carbon::createFromFormat('d/m/Y H:i:s',$start_date.' 23:59:59')->subDays(1)->format('d/m/Y');
            $ytd = \App\Calculations\Base\Slips::getLastYTDByAgent($agent,$endoflastytd,$this->minus_session_name,'TopOverriding');
            $ovr = new TopOverriding($agent, $start_date , $end_date);
            if (\Session::has($this->minus_session_name . $agent->id)) {
                $ovr->minus = \Session::get($this->minus_session_name . $agent->id)['value'];
                \Session::forget($this->minus_session_name . $agent->id);
            }
            $ovr->calculate($recalc);
            $ovr->last_YTD = $ytd;
            $ovr->tax = \App\Calculations\Base\Slips::calculateTax($agent, $ytd, $ovr->gross_commission/2, 0);
            $ovrs[] = $ovr;
            $allsalecom += count($ovr->sales);
        }
        if($allsalecom <= 0){
            \Flash::error("Rec Fee report for period $start_date until $end_date is not available");
            return redirect()->back();
        }
        $html = \View::make('pdf.slips.topoverriding', compact('ovrs', 'start_date','end_date'))->render();
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
