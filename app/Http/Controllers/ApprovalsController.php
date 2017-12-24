<?php

namespace App\Http\Controllers;

use App\Action;
use App\Approval;
use App\CommissionReport;
use App\Customer;
use App\Reminder;
use App\Repositories\AuditRepository as Audit;
use App\Sale;
use Auth;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Nayjest\Grids\EloquentDataProvider;

class ApprovalsController extends Controller
{

    /**
     * @var Approval
     */
    protected $approval;

    public function __construct(Approval $approval)
    {
        $this->approval  = $approval;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('approval/general.audit-log.category'), trans('approval/general.audit-log.msg-index'));

        $page_title = trans('approval/general.page.index.title');
        $page_description = trans('approval/general.page.index.description');

        $dataProvider = new EloquentDataProvider(Approval::with('user')->where('is_approved', 0));

        return view('approval.index', compact('dataProvider', 'page_title', 'page_description', 'enabledOnly'));
    }

    public function approve($id){
        $approval = $this->approval->find($id);
        $approvalContentArr = json_decode($approval->TrueDescription, true);

        Audit::log(Auth::user()->id, request()->ip(), trans('approval/general.audit-log.category'), trans('approval/general.audit-log.msg-approved', ['ID' => $id]), $approval->toArray());

        if($approval->subject == "Add New Customer") {


        } elseif($approval->subject == "Add New Agent") {

        }
        elseif($approval->subject == "Add New Sales") {

        }

        // if($approval->subject != "Insert Sale Backdate") {
        //     $query = "update " . $approvalContentArr['table'] . ' set ';
        //     $i = 0;
        //     foreach ($approvalContentArr as $key => $value) {
        //         if ($key != "table" && $key != "id") {
        //             $query .= $key . " = " . $value;
        //             if ($i < count($approvalContentArr) - 3) $query .= ", ";
        //         }
        //         $i++;
        //     }
        //     $query .= " where id = " . $approvalContentArr['id'];
        // }
        //
        // if($approval->subject == "Sales Target Evaluation") {
        //     //for Sales Target Evaluation, insert to table Action
        //     Action::create([
        //         'action' => $query,
        //         'execution_date' => (Carbon::now()->month == 1 ? Carbon::create(Carbon::now()->year, 2, 1)->format('d/m/Y') : Carbon::create(Carbon::now()->year, 8, 1)->format('d/m/Y'))
        //     ]);
        //
        // } elseif($approval->subject == "Change Agent Parent") {
        //     Action::create([
        //         'action' => $query,
        //         'execution_date' => CommissionReport::getNextPeriodStartDate()->format('d/m/Y')
        //     ]);
        //
        // } elseif($approval->subject == "Change Agent Position") {
        //     Action::create([
        //         'action' => $query,
        //         'execution_date' => CommissionReport::getNextPeriodStartDate()->format('d/m/Y')
        //     ]);
        //     $agent = \App\Agent::find($approvalContentArr['id']);
        //     $newAgentPositionId = $approvalContentArr['agent_position_id'];
        //     $oldAgentPositionId = $agent->agent_position_id;
        //     $old_agent_position = \App\AgentPosition::find($oldAgentPositionId);
        //     $new_agent_position = \App\AgentPosition::find($newAgentPositionId);
        //     if($old_agent_position->level > $new_agent_position->level) { // naik
        //         $parent_agent_position = \App\AgentPosition::find($agent->parent->agent_position_id);
        //         $to_be_parent_id = $agent->parent->parent_id;
        //         if($new_agent_position->level > $parent_agent_position->level || $to_be_parent_id == null) {
        //             $to_be_parent_id = $agent->parent_id;
        //         }
        //         Action::create([
        //             'action' => "UPDATE agents SET parent_id = '$to_be_parent_id' WHERE id = '$id'",
        //             'execution_date' => CommissionReport::getNextPeriodStartDate()->format('d/m/Y')
        //         ]);
        //     } else if($old_agent_position->level < $new_agent_position->level){ // turun
        //         Action::create([
        //             'action' => "UPDATE agents SET parent_id = '$agent->parent_id' WHERE parent_id = '$id' AND agent_position_id = '$newAgentPositionId'",
        //             'execution_date' => CommissionReport::getNextPeriodStartDate()->format('d/m/Y')
        //         ]);
        //     }
        //     Action::create([
        //         'action' => "UPDATE agents SET agent_position_id = '$newAgentPositionId' WHERE id = '$id'",
        //         'execution_date' => CommissionReport::getNextPeriodStartDate()->format('d/m/Y')
        //     ]);
        //
        // } elseif($approval->subject == "Insert Sale Backdate"){
        //     $attributes = json_decode($approvalContentArr["attributes"], true);
        //
        //     $sale = Sale::create($attributes);
        //
        //     $sale->customer_name = Customer::getCustomerFromId($sale->customer_id)->name;
        //     $sale->customer_DOB = Customer::getCustomerFromId($sale->customer_id)->DOB;
        //     $sale->agent_commission = Sale::getSaleCommissionPercentage($sale);
        //     $sale->save();
        //
        //     Sale::updateMGIMonth($sale);
        //
        // }elseif($approval->subject == "Change Sales MGI/Nominal"){
        //     $sale = Sale::getSaleFromId($approvalContentArr["id"]);
        //     $sale->update($approvalContentArr);
        //     Sale::updateMGIMonth($sale);
        //
        // } else {
        //     //execute now
        //     DB::raw($query);
        // }

        $approval->is_approved = 1;
        $approval->save();

        if($approval->subject != "Insert Sale Backdate") {
            $content = [
                'table' => $approvalContentArr['table'],
                'id' => $approvalContentArr['id'],
                'message' => 'Approval for <b>' . $approval->subject .
                    '</b> (' . $approvalContentArr['table'] . ' <b>#' . $approvalContentArr['id'] .'</b>)' .
                    ' approved at ' .  Carbon::now()->toDateTimeString() . '.'
            ];

        }else{
            $content = [
                'table' => $approvalContentArr['table'],
                'id' => $sale->id,
                'message' => 'Approval for <b>' . $approval->subject .
                    '</b> (' . $approvalContentArr['table'] . ' number <b>#' . $sale->number .'</b>)' .
                    ' approved at ' .  Carbon::now()->toDateTimeString() . '.'
            ];
        }

        Reminder::create([
            'title' => 'Approval Approved',
            'reminder_for' => 'admin',
            'start_date' => Carbon::now()->format('d/m/Y'),
            'end_date' => Carbon::now()->addDays(2)->format('d/m/Y'),
            'subject' => 'approval',
            'content' => json_encode($content),
            'respond_link' => '#'
        ]);

        Flash::success(trans('approval/general.status.approved'));
        return redirect()->back();
    }

    public function delete($id)
    {
        $approval = $this->approval->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('approval/general.audit-log.category'), trans('approval/general.audit-log.msg-disabled', ['ID' => $id]), $approval->toArray());

        $approval->delete();

        Flash::success(trans('approval/general.status.deleted'));
        return redirect()->back();
    }


    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('approval/general.audit-log.category'), trans('approval/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $approval = $this->approval->find($user_id);
                $approval->delete();
            }
            Flash::success(trans('approval/general.status.global-deleted'));
        }
        else
        {
            Flash::warning(trans('approval/general.status.no-approval-selected'));
        }
        return redirect()->back();
    }

}
