<?php

namespace App\Http\Controllers;

use App\Calculations\Recap;
use App\RecapReport;
use Illuminate\Http\Request;
use App\Repositories\AuditRepository as Audit;
use Auth;
use Input;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RecapSlipsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/recap/general.audit-log.category'), trans('slips/recap/general.audit-log.msg-index'));

        $page_title = trans('slips/recap/general.page.index.title');
        $page_description = trans('slips/recap/general.page.index.description');
        $agent_position_lists = \App\AgentPosition::lists('name', 'id')->all();

        $month_lists = [];
        for( $i = 1; $i <= 12; $i++ ) {
            $month_num = str_pad($i, 2, 0, STR_PAD_LEFT);
            $month_name = strftime('%B', mktime(0, 0, 0, $i + 1, 0, 0));
            $month_lists[$month_num] = $month_name;
        }
        $year_lists = array_combine(range(2016, date('Y')), range(2016, date('Y')));

        return view('slips.recap.index', compact('agent_position_lists', 'month_lists', 'year_lists', 'page_title', 'page_description'));
    }

    public function export(\App\Http\Requests\RecapExcelExport $export)
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('slips/recap/general.audit-log.category'), trans('slips/recap/general.audit-log.msg-export'));

        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        $builder = \App\Agent
            ::with('sales.customer')
            ->with('parent')
            ->with('branchOffice')
            ->with('agent_position')
            ->whereHas('sales', function ($query) {
              $start_date = Input::get('start_date');
              $end_date = Input::get('end_date');
                $query->where('MGI_start_date', '>=', Carbon::createFromFormat('d/m/Y', $start_date)->subDay())
                ->where('MGI_start_date', '<=', Carbon::createFromFormat('d/m/Y', $end_date))
                ->orderBy('MGI_start_date', 'asc');
            })
            ->where('is_active', '=', 1)
            ->orderBy('agent_position_id', 'desc');

        if($start_date == "" || $end_date == "")
        {
          \Flash::error("Recapitulation report for period $start_date until $end_date is not available.");
          return redirect()->back();
        }

        $agents = $builder->get();
  			$export->data = $agents;
  			return $export->handleExport();

    }
}
