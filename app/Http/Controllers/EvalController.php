<?php

namespace App\Http\Controllers;

use App\Agent;
use Auth;
use App\Repositories\AuditRepository as Audit;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EvalController extends Controller
{

    public function index()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('approval/general.audit-log.category'), trans('approval/general.audit-log.msg-index'));


        // TODO: TESTING: change $allowed to true
        $allowed = false;
        //June 30th - July 5th
        if(Carbon::now() >= Carbon::create(Carbon::now()->year, 6, 30) &&Carbon::now() <= Carbon::create(Carbon::now()->year, 7, 5)){
            $allowed = true;

        }else{
            //if now = december and DayOfMonth > dec 30th
            if(Carbon::now()->month == 12 && Carbon::now()->day >= 30){
                $allowed = true;
                //if now == january and DayOfMonth < jan 10th
            }elseif(Carbon::now()->month == 1 && Carbon::now()->day <= 10){
                $allowed = true;
            }
        }

        if($allowed){
            $page_title = trans('eval/general.page.index.title');
            $page_description = trans('eval/general.page.index.description');


            $data = Agent::evaluateAgents();

            return view('eval.index', compact('data', 'page_title', 'page_description', 'enabledOnly'));

        }else{
            \Flash::error('Sales Target Evaluation data not available right now.');
            return redirect()->back();
        }

    }
}
