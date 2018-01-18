<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAgentRequest;
use App\Http\Requests\EditAgentRequest;
use App\Repositories\AgentRepository as Agent;
use App\Repositories\AuditRepository as Audit;
use Nayjest\Grids\EloquentDataProvider;
use Flash;
use Auth;
use Input;
use PDF;
use App\Approval;
use App\Action;
use App\CommissionReport;
use App\Http\Requests\AgentsExcelExport;

class AgentsController extends Controller
{
    /**
     * @var Agent
     */
    protected $agent;

    public function __construct(Agent $agent)
    {
        $this->agent  = $agent;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enabledOnly = 1)
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-index'));

        $page_title = trans('agents/general.page.index.title');
        $page_description = trans('agents/general.page.index.description');

        $dataProvider = \App\Agent::getIndexDataProvider($enabledOnly);
        $modelColumns = \App\Agent::getColumnsArray();
        $agent_position_lists = \App\AgentPosition::lists('name', 'id');
		$branch_office_id_lists = \App\BranchOffice::lists('branch_name','id');
        return view('agents.index', compact('dataProvider', 'page_title', 'modelColumns', 'agent_position_lists', 'page_description', 'enabledOnly','branch_office_id_lists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-create'));

        $page_title = trans('agents/general.page.create.title');
        $page_description = trans('agents/general.page.create.description');

        $agent = new \App\Agent();
        $agent_position_lists = \App\AgentPosition::lists('name', 'id')->all();

        $current_agent_count = \App\Agent::isActive()->whereIn('agents.branch_office_id', \App\BranchOffice::getBranchOfficesID())->count();
        $agent_lists = ['none' => 'None'] + \App\Agent::getAgentsWithPositionName_ForDropDown();
		$typeConfig = config('types');
        $types = [];
        //$types[null] = "Pilih Tipe";
        foreach($typeConfig as $key => $value){
            $types[$key] = $value;
        }
        return view('agents.create', compact('agent', 'agent_position_lists', 'agent_lists', 'page_title', 'page_description','types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateAgentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAgentRequest $request)
    {
        $attributes = $request->all();
        if(isset($attributes['parent_id'])) {
            // normalize if agent has no parent
            if($attributes['parent_id'] == 'none') $attributes['parent_id'] = null;
        }

        Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-store', ['NIK' => $attributes['NIK']]), $attributes);
        // prepare newCustomer, Customer instance
        $newAgent = $this->agent->create($attributes);

        if(Auth::getUser()->hasRole('owner'))
        {
          $newAgent->is_active = 1;
        }
        else {
          $newAgent->is_active = 2;
          $branchName = BranchOffice::getBranchOfficeFromId($attributes["branch_office_id"])->branch_name;
          $approvalAttributes = [];
          $approvalAttributes["user_id"] = Auth::user()->id;
          $approvalAttributes["subject"] = "Add New Agent";
          $approvalAttributes["description"] = "<a href='".route('agents.show', ['id' => $attributes["id"]]).">".$attributes["id"]."-".$attributes["name"]."</a> ($branchName)";
          $approvalAttributes["is_approved"] = 0;
          $newApproval = Approval::create($approvalAttributes);
          $newApproval->save($approvalAttributes);
        }
        $newAgent->save($attributes);
        Flash::success( trans('agents/general.audit-log.msg-store', ['NIK' => $attributes['NIK']]) );

        return redirect('/agents/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agent = \App\Agent::getAgentFromId($id);

        if($agent != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-show', ['ID' => $agent->id]));

            $page_title = trans('agents/general.page.show.title');
            $page_description = trans('agents/general.page.show.description', ['name' => $agent->name]);
            $agent_position_lists = \App\AgentPosition::lists('name', 'id');
            $agent_lists = ['none' => 'None'] + \App\Agent::getAgentsWithPositionName_ForDropDown();
            $agent_sales_provider = new EloquentDataProvider(\App\Sale::where('agent_id', $id));
            $child_agent_provider = new EloquentDataProvider(\App\Agent::with('agent_position')->where('parent_id', $id));
			$typeConfig = config('types');
            return view('agents.show', compact('agent', 'agent_lists', 'agent_position_lists', 'agent_sales_provider', 'child_agent_provider', 'page_title', 'page_description'));

        } else {
            Flash::error( trans('agents/general.error.no-data') );
            return redirect('agents/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $agent = \App\Agent::getAgentFromId($id);
        if($agent != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-edit', ['ID' => $agent->id]));

            $page_title = trans('agents/general.page.edit.title');
            $page_description = trans('agents/general.page.edit.description', ['name' => $agent->name]);
            $agent_position_lists = \App\AgentPosition::lists('name', 'id');
            $agent_lists = ['none' => 'None'] + \App\Agent::getAgentsWithPositionName_ForDropDown();
			$typeConfig = config('types');
			$types = [];
			//$types[null] = "Pilih Tipe";
			foreach($typeConfig as $key => $value){
				$types[$key] = $value;
			}
            return view('agents.edit', compact('agent', 'agent_position_lists', 'agent_lists', 'page_title', 'page_description','types'));

        }else{
            Flash::error( trans('agents/general.error.no-data') );
            return redirect('agents/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditAgentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditAgentRequest $request, $id)
    {
        $agent = $this->agent->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-update', ['ID' => $id]), $agent->toArray());

        $oldFilename = $agent->id_card_image_filename;
        $oldAgentPositionId = $agent->agent_position_id;
        $oldParentId = $agent->parent_id;

        $attributes = $request->all();
        if(isset($attributes['parent_id'])) {
            // normalize if agent has no parent
            if($attributes['parent_id'] == 'none') $attributes['parent_id'] = null;
        }

        $message = "";
        if(\Auth::user()->hasRole('admin')) {
            if($attributes['parent_id'] != $oldParentId) {
                Approval::create([
                    'user_id' => Auth::getUser()->id,
                    'subject' => 'Change Agent Parent',
                    'description' => json_encode([
                        'parent_id' => $attributes['parent_id'],
                        'id' => $attributes['id'],
                        'table' => (new \App\Agent())->getTable(),
                    ]),
                ]);
                $message .= trans('agents/general.audit-log.msg-update-approval', ['ID' => $attributes['id'], 'key' => 'parent_id']);
                unset($attributes['parent_id']);
            }
            if($attributes['agent_position_id'] != $oldAgentPositionId) {
                Approval::create([
                    'user_id' => Auth::getUser()->id,
                    'subject' => 'Change Agent Position',
                    'description' => json_encode([
                        'agent_position_id' => $attributes['agent_position_id'],
                        'id' => $attributes['id'],
                        'table' => (new \App\Agent())->getTable(),
                    ]),
                ]);
                $message .= trans('agents/general.audit-log.msg-update-approval', ['ID' => $attributes['id'], 'key' => 'agent_position_id']);
                unset($attributes['agent_position_id']);
            }
        } else if(\Auth::user()->hasRole('owner')) {
            if($attributes['parent_id'] != $oldParentId) {
                $newParentId = $attributes['parent_id'];
                Action::create([
                    'action' => "UPDATE agents SET parent_id = '$newParentId' WHERE id = '$id'",
                    'execution_date' => CommissionReport::getNextPeriodStartDate()->format('d/m/Y')
                ]);
                $message .= trans('agents/general.audit-log.msg-update-delay', ['ID' => $attributes['id'], 'key' => 'parent_id']);
                unset($attributes['parent_id']);
            }
            if($attributes['agent_position_id'] != $oldAgentPositionId) {
                $newAgentPositionId = $attributes['agent_position_id'];
                $old_agent_position = \App\AgentPosition::find($oldAgentPositionId);
                $new_agent_position = \App\AgentPosition::find($newAgentPositionId);
                if($old_agent_position->level > $new_agent_position->level) { // naik
                    $parent_agent_position = \App\AgentPosition::find($agent->parent->agent_position_id);
                    $to_be_parent_id = $agent->parent->parent_id;
                    if($new_agent_position->level > $parent_agent_position->level || $to_be_parent_id == null) {
                        $to_be_parent_id = $agent->parent_id;
                    }
                    Action::create([
                        'action' => "UPDATE agents SET parent_id = '$to_be_parent_id' WHERE id = '$id'",
                        'execution_date' => CommissionReport::getNextPeriodStartDate()->format('d/m/Y')
                    ]);
                } else if($old_agent_position->level < $new_agent_position->level) { // turun
                    Action::create([
                        'action' => "UPDATE agents SET parent_id = '$agent->parent_id' WHERE parent_id = '$id' AND agent_position_id = '$newAgentPositionId'",
                        'execution_date' => CommissionReport::getNextPeriodStartDate()->format('d/m/Y')
                    ]);
                }
                Action::create([
                    'action' => "UPDATE agents SET agent_position_id = '$newAgentPositionId' WHERE id = '$id'",
                    'execution_date' => CommissionReport::getNextPeriodStartDate()->format('d/m/Y')
                ]);
                $message .= trans('agents/general.audit-log.msg-update-delay', ['ID' => $attributes['id'], 'key' => 'agent_position_id']);
                unset($attributes['agent_position_id']);
            }
        }

        $agent->update($attributes);
        Flash::success( trans('agents/general.audit-log.msg-update', ['ID' => $attributes['id']]) . "<br />" . $message );
        return redirect()->route('agents.edit', ['id' => $id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $agent = $this->agent->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-enable', ['ID' => $id]), $agent->toArray());

        $agent->is_active = 1;
        $agent->save();

        Flash::success(trans('agents/general.status.enabled'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $agent = $this->agent->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-disabled', ['ID' => $id]), $agent->toArray());

        $agent->is_active = 0;
        $agent->save();
        \App\Agent::changeParentFromDisable($agent);

        Flash::success(trans('agents/general.status.disabled'));
        return redirect()->back();
    }

    public function history()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-history'));

        $page_title = trans('agents/general.page.history.title');
        $page_description = trans('agents/general.page.history.description');

        $dataProvider = \App\AgentPositionHistory::getIndexDataProvider();
        return view('agents.history', compact('dataProvider', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-enabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $agent = $this->agent->find($user_id);
                $agent->is_active = 1;
                $agent->save();
            }
            Flash::success(trans('agents/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('agents/general.status.no-agent-selected'));
        }
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $agent = $this->agent->find($user_id);
                $agent->is_active = 0;
                $agent->save();
                \App\Agent::changeParentFromDisable($agent);
            }
            Flash::success(trans('agents/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('agents/general.status.no-agent-selected'));
        }
        return redirect()->back();
    }

    public function summary() {
        Audit::log(Auth::user()->id, request()->ip(), trans('agents/general.audit-log.category'), trans('agents/general.audit-log.msg-summarypage'));

        $page_title = trans('agents/general.page.summary.title');
        $page_description = trans('agents/general.page.summary.description');
        $builder = \App\Agent
            ::with('parent')
            ->with('childrenRecursive')
            ->with('sales')
            ->where('is_active', '=', 1)
            ->whereIn('agents.branch_office_id', \App\BranchOffice::getBranchOfficesID());

        if(Input::has('name')) {
            $param = Input::get('name');
            $builder->where('name', 'LIKE', "%$param%");
        }
        if(Input::has('agent_code')) {
            $param = Input::get('agent_code');
            $builder->where('agent_code', 'LIKE', "%$param%");
        }
        if(!Input::has('name') && !Input::has('agent_code')) {
            $builder->where('parent_id', '=', null);
        }

        $agents = $builder->get();

        $agent_lists = ['all' => 'All'] + \App\Agent::getAgents_ForDropDown();

        return view('agents.summary', compact('agents', 'agent_lists', 'agent_position_lists', 'page_title', 'page_description'));
    }

    public function export(AgentsExcelExport $export)
    {
        // TODO: change pdf generator to manual attribute printing, get agent position name
        if(Input::has('type')) {
            $builder = \App\Agent::with('agent_position')->where('is_active', 1);

            if(Input::has('created_at_filter1') && Input::has('created_at_filter2')) {
                // apply join date filter
				//$builder->whereBetween('created_at',[date_format(date_create(Input::get('created_at_filter1')),'d/m/Y'),date_format(date_create(Input::get('created_at_filter2')),'d/m/Y')]);
                $builder->whereBetween('created_at', [
                    \DateTime::createFromFormat('d/m/Y', Input::get('created_at_filter1')),
                    \DateTime::createFromFormat('d/m/Y', Input::get('created_at_filter2'))
                ]);
            }
            if(Input::has('chkExp')) {
                // apply checkbox column filter
                $builder->select(Input::get('chkExp'));
            }
            if(Input::has('agent_position_id') && Input::get('agent_position_id') != 'all') {
                // apply agent id filter
                $builder->where('agent_position_id', Input::get('agent_position_id'));
            }

            $builder->whereIn('branch_office_id',\App\BranchOffice::getBranchOfficesID());
			if(Input::has('branch_office_id') && Input::get('branch_office_id') != 'all') {
				$builder->whereIn('branch_office_id',Input::get('branch_office_id'));
			}
			if(Input::has('agent_name_filter')) {
                // apply checkbox column filter
                $builder->where('name', 'like', '%' . Input::get('agent_name_filter') . '%');
            }
			if(Input::has('leader_name_filter')) {
                // apply checkbox column filter
				$parentname = Input::get('leader_name_filter');
				$parent = \App\Agent::getAgentFromName($parentname);
                $builder->whereIn('parent_id',$parent);
            }
			
			$data = $builder->get();
			dd($data);
            $columns = Input::get('chkExp');
            if($data->count() == 0) {
                Flash::error( trans('agents/general.error.no-data') );
                return redirect()->back();
            }
            switch(Input::get('type')) {
                case 'pdf':
                default:
                    $html = \View::make('pdf.agents', compact('data', 'columns', 'enabledOnly'))->render();
                    // $html = str_replace('id=', 'class=', $html); // DOMPDF workaround -> https://github.com/barryvdh/laravel-dompdf/issues/96
                    // $pdf = \App::make('dompdf.wrapper');
                    // $pdf->loadHtml($html);
                    // $pdf->setPaper('A4', 'landscape');
                    // return $mpdf->stream('agents.pdf');

                    $mpdf = new \mPDF("en", "A4-L", "12");
					dd($html);
                    $mpdf->WriteHTML($html);
                    return $mpdf->Output();
                case 'xlsx':
                    $dataArray = $data->toArray();
                    $export->data = $dataArray;
                    return $export->handleExport();
            }
        } else {
            Flash::error( trans('agents/general.error.no-data') );
            return redirect()->back();
        }
    }

    public function summary_export()
    {
        $builder = \App\Agent
            ::with('parent')
            ->with('childrenRecursive')
            ->with('sales')
            ->where('is_active', '=', 1);

        if(Input::has('agent_id')) {
            $param = Input::get('agent_id');
            $builder->where('id', '=', $param);
        }
        if(!Input::has('agent_id')) {
            $builder->where('parent_id', '=', null);
        }
        $agents = $builder->get();

        if($agents->count() == 0) {
            Flash::error( trans('agents/general.error.no-data') );
            return redirect()->back();
        }

        $html = \View::make('pdf.summaryagents', compact('agents'))->render();
        // $html = str_replace('id=', 'class=', $html); // DOMPDF workaround -> https://github.com/barryvdh/laravel-dompdf/issues/96
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHtml($html);
        $pdf->setPaper('A4');
        $pdf->setOrientation('portrait');
        return $pdf->stream();
    }


}
