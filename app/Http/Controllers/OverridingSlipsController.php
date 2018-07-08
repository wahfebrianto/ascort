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

class OverridingSlipsController extends Controller
{

    private $minus_session_name = 'ovr.minus.';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/overriding/general.audit-log.category'), trans('slips/overriding/general.audit-log.msg-index'));

        $page_title = trans('slips/overriding/general.page.index.title');
        $page_description = trans('slips/overriding/general.page.index.description');
        $agent_position_lists = \App\AgentPosition::withoutLowest()->lists('name', 'id')->all();
        $agent_lists = ['all' => 'All'] + \App\Agent::isActive()->whereIn('agent_position_id', array_keys($agent_position_lists))->lists('name', 'id')->all();

        $month_lists = [];
        for( $i = 1; $i <= 12; $i++ ) {
            $month_num = str_pad($i, 2, 0, STR_PAD_LEFT);
            $month_name = strftime('%B', mktime(0, 0, 0, $i + 1, 0, 0));
            $month_lists[$month_num] = $month_name;
        }
        $year_lists = array_combine(range(2016, date('Y')), range(2016, date('Y')));

        return view('slips.overriding.index', compact('agent_position_lists', 'agent_lists', 'month_lists', 'year_lists', 'page_title', 'page_description'));
    }

    public function minus(Request $request)
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $isCalculated = CommissionReport::isSlipCalculated($period, $month, $year, Overriding::TYPE);
        if(!CommissionReport::isAllowedToView($period, $month, $year)) {
            \Flash::error("Overriding report for period $period, $month $year is not available.");
            return redirect()->back();
        }
        if(!$request->has('recalc') && $isCalculated) {
            return redirect(route('slips.overriding.export') . '?' . $_SERVER['QUERY_STRING']);
        } else {
            if(!CommissionReport::isRecalculationAllowed($period, $month, $year) && $isCalculated) {
                \Flash::error("Recalculation for commission report for period $period, $month $year is not allowed.");
                return redirect(route('slips.overriding.index'));
            }
        }

        $page_title = trans('slips/overriding/general.page.index.title');
        $page_description = trans('slips/overriding/general.page.index.description');

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

        $agent_position_lists = \App\AgentPosition::withoutLowest()->lists('name', 'id')->all();
        $builder = \App\Agent
            ::where('is_active', '=', 1)
            ->whereIn('agent_position_id', array_keys($agent_position_lists));
        if(null != Input::get('agent_id') && Input::get('agent_id') != 'all') {
            $builder->where('id', '=', Input::get('agent_id'));
        }

        if(null != Input::get('dist_channel')) {
            $builder->where('dist_channel', 'like', '%' . Input::get('dist_channel') . '%');
        }
        $agent_lists = $builder->lists('name', 'id');

        return view('slips.overriding.minus', compact('agent_lists', 'page_title', 'page_description'));
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

        Audit::log(Auth::user()->id, request()->ip(), trans('slips/overriding/general.audit-log.category'), trans('slips/overriding/general.audit-log.msg-export'));
        $agent_position_lists = \App\AgentPosition::withoutLowest()->lists('name', 'id')->all();
        $builder = \App\Agent
            ::with('childrenRecursive')
            ->with('sales')
            ->with('parent')
            ->where('is_active', '=', 1)
            ->whereIn('agent_position_id', array_keys($agent_position_lists))
            ->orderBy('agent_position_id', 'desc');
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        if($start_date == "" or $end_date == ""){
            \Flash::error("Overriding report for period $start_date until $end_date is not available");
            return redirect()->back();
        }
        // $isCalculated = CommissionReport::isSlipCalculated($period, $month, $year, Overriding::TYPE);
        $recalc = false;

        if(null != Input::get('agent_position_id') && Input::get('agent_position_id') != 'all') {
            $builder->where('agent_position_id', '=', Input::get('agent_position_id'));
        }
        if(null != Input::get('agent_id') && Input::get('agent_id') != 'all') {
            $builder->where('id', '=', Input::get('agent_id'));
        }
        if(null != Input::get('dist_channel')) {
            $builder->where('dist_channel', 'like', '%' . Input::get('dist_channel') . '%');
        }
        $agents = $builder->get();
        $recalc = true;
        // if(\Input::has('recalc')) {
        //     if(!CommissionReport::isRecalculationAllowed($period, $month, $year) && $isCalculated) {
        //         \Flash::error("Recalculation for commission report for period $period, $month $year is not allowed.");
        //         return redirect(route('slips.overriding.index'));
        //     }
        //     $recalc = true;
        // }

        $ovrs = [];
        $allsalecom = 0;
        foreach($agents as $agent) {
            $endoflastytd =Carbon::createFromFormat('d/m/Y H:i:s',$start_date.' 23:59:59')->subDays(1)->format('d/m/Y');
            $ytd = \App\Calculations\Base\Slips::getLastYTDByAgent($agent,$endoflastytd,$this->minus_session_name,'OR');
            $ovr = new Overriding($agent, $start_date , $end_date);
            if (\Session::has($this->minus_session_name . $agent->id)) {
                $ovr->minus = \Session::get($this->minus_session_name . $agent->id)['value'];
                \Session::forget($this->minus_session_name . $agent->id);
            }
            $ovr->calculate($recalc);
            //$ovr->last_YTD = $ytd;
            //$ovr->tax = \App\Calculations\Base\Slips::calculateTax($agent, $ytd, $ovr->gross_commission/2, 0);
            $ovrs[] = $ovr;
            $allsalecom += count($ovr->sales);
        }
        if($allsalecom <= 0){
            \Flash::error("Commission report for period $start_date until $end_date is not available");
            return redirect()->back();
        }
        $html = \View::make('pdf.slips.overriding', compact('ovrs', 'start_date','end_date'))->render();
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
