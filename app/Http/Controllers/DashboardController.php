<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Approval;
use App\Money;
use App\Reminder;
use App\Sale;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AuditRepository as Audit;

class DashboardController extends Controller
{

    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Protect all dashboard routes. Users must be authenticated.
        $this->middleware('auth');
    }

    public function index() {
        $page_title = "Dashboard";
        $page_description = "This is the dashboard";

        $count_customers = \App\Customer::isActive()->whereIn('branch_office_id', \App\BranchOffice::getBranchOfficesID())->count();
        $count_agents = \App\Agent::isActive()->whereIn('branch_office_id', \App\BranchOffice::getBranchOfficesID())->count();
        $current_period = \App\CommissionReport::getCurrentPeriodCode();
        $count_sales = \App\Sale::isActive()->whereIn('branch_office_id', \App\BranchOffice::getBranchOfficesID())->ofPeriod(substr($current_period, -1), date('m'), date('Y'))->count();
        $holidays = \App\Holiday::select('date')->get()->toArray();
        $holidays_arr = [];
        foreach($holidays as $holiday) $holidays_arr[] = $holiday['date'];
        $holidays_date_json = json_encode($holidays_arr);
        $dataProvider = \App\Sale::getDashboardDataProvider();


        if(Auth::user()->hasRole('admin')){
            $reminderEval = Reminder::getEvalReminders();
            $reminderRollover = Reminder::getRolloverReminders();
            $reminderApproval = Reminder::getApprovalReminders();
            $totalApproval = 0;
        }else{
            $reminderEval = [];
            $reminderRollover = [];
            $reminderApproval = [];
            $totalApproval = Approval::countUnapproved();
        }
        $reminder_count = count($reminderApproval) + count($reminderRollover) + count($reminderEval) + $totalApproval;


        return view('dashboard', compact('page_title', 'page_description', 'reminderEval', 'totalApproval', 'reminder_count',
            'reminderRollover', 'reminderApproval', 'count_customers', 'count_agents', 'count_sales', 'current_period', 'holidays_date_json', 'dataProvider'));
    }

    public function changeagent(Request $request){
        $details = [];
        $details["table"] = "agents";
        $details["id"] = $request->agent_id;
        $details["agent_position_id"] = $request->agent_position_id;

        Approval::create([
            'user_id' => Auth::user()->id,
            'subject' => 'Sales Target Evaluation',
            'description' => json_encode($details)
        ]);
        Reminder::inactivateReminder(Reminder::getReminderById($request->id));

        \Flash::success('Agent position change is waiting for owner\'s approval.');
        return redirect('dashboard');
    }

    public function dismissreminder($id){
        Reminder::inactivateReminder(Reminder::getReminderById($id));
        \Flash::success('Reminder dismissed.');
        return redirect('dashboard');
    }

}
