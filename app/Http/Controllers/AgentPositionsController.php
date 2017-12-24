<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAgentPositionRequest;
use App\Http\Requests\EditAgentPositionRequest;
use App\Repositories\AuditRepository as Audit;
use App\AgentPosition;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Auth;
use Illuminate\Support\Collection;
use Nayjest\Grids\EloquentDataProvider;

class AgentPositionsController extends Controller
{

    /**
     * @var AgentPosition
     */
    protected $position;

    public function __construct(AgentPosition $position)
    {
        $this->position  = $position;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enabledOnly = 1)
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/agentpos/general.audit-log.category'), trans('admin/agentpos/general.audit-log.msg-index'));

        $page_title = trans('admin/agentpos/general.page.index.title');
        $page_description = trans('admin/agentpos/general.page.index.description');

        $dataProvider = new EloquentDataProvider(AgentPosition::with('parent')->where('is_active', $enabledOnly));
        $jstreeJson = $this->jstreeJson($dataProvider->getCollection());

        return view('admin.agentpos.index', compact('dataProvider', 'jstreeJson', 'page_title', 'page_description', 'enabledOnly'));
    }

    /**
     * Prepare json data for jstree json visualization
     *
     * @return string
     */
    private function jstreeJson(Collection $positions)
    {
        $jsTreeCol = $positions->map(function ($item, $key) {
            $id             = $item->id;
            $parent_id      = $item->parent['id'];
            $label          = $item->name;
            $icon           = 'fa fa-briefcase';
            // Fix attribute of root item for JSTree
            if ( (empty($parent_id)) ) {
                $parent_id = '#';
            }
            return collect(['id' => $id, 'parent' => $parent_id, 'text' => $label, 'icon' => $icon]);
        });

        $json = $jsTreeCol->toJson();

        return $json;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/agentpos/general.audit-log.category'), trans('admin/agentpos/general.audit-log.msg-create'));

        $page_title = trans('admin/agentpos/general.page.create.title');
        $page_description = trans('admin/agentpos/general.page.create.description');

        $position = new AgentPosition();

        return view('admin.agentpos.create', compact('position', 'page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateAgentPositionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAgentPositionRequest $request)
    {
        $attributes = $request->all();
        if($attributes["parent_id"] == "-") $attributes["parent_id"] = null;

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/agentpos/general.audit-log.category'), trans('admin/agentpos/general.audit-log.msg-store', ['name' => $attributes['name']]), $attributes);

        // prepare newAgentPosition, AgentPosition instance
        $newAgentPosition = $this->position->create($attributes);
        $newAgentPosition->save($attributes);
        Flash::success( trans('admin/agentpos/general.audit-log.msg-store', ['name' => $attributes['name']]) );

        return redirect('/admin/agentpos/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $position = \App\AgentPosition::getAgentPositionFromId($id);
        if($position != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('admin/agentpos/general.audit-log.category'), trans('admin/agentpos/general.audit-log.msg-show', ['ID' => $position->id]));

            $page_title = trans('admin/agentpos/general.page.show.title');
            $page_description = trans('admin/agentpos/general.page.show.description', ['name' => $position->name]);

            return view('admin.agentpos.show', compact('position', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('admin/agentpos/general.error.no-data') );
            return redirect('admin/agentpos/');
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
        $position = \App\AgentPosition::getAgentPositionFromId($id);
        if($position != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('admin/agentpos/general.audit-log.category'), trans('admin/agentpos/general.audit-log.msg-edit', ['name' => $position->name]));

            $page_title = trans('admin/agentpos/general.page.edit.title');
            $page_description = trans('admin/agentpos/general.page.edit.description', ['name' => $position->name]);

            return view('admin.agentpos.edit', compact('position', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('admin/agentpos/general.error.no-data') );
            return redirect('admin/agentpos/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditAgentPositionRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditAgentPositionRequest $request, $id)
    {
        $position = $this->position->find($id);
        $attributes = $request->all();
        if($attributes["parent_id"] == "-") $attributes["parent_id"] = null;

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/agentpos/general.audit-log.category'), trans('admin/agentpos/general.audit-log.msg-update', ['ID' => $attributes['id']]), $position->toArray());

        $position->update($attributes);
        Flash::success( trans('admin/agentpos/general.audit-log.msg-update', ['name' => $attributes['name']]) );
        return redirect()->route('admin.agentpos.edit', ['id' => $id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $position = $this->position->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/agentpos/general.audit-log.category'), trans('admin/agentpos/general.audit-log.msg-enable', ['ID' => $id]), $position->toArray());

        $position->is_active = 1;
        $position->save();

        Flash::success(trans('admin/agentpos/general.status.enabled'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $position = $this->position->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/agentpos/general.audit-log.category'), trans('admin/agentpos/general.audit-log.msg-disabled', ['ID' => $id]), $position->toArray());

        $position->is_active = 0;
        $position->save();

        Flash::success(trans('admin/agentpos/general.status.disabled'));
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/agentpos/general.audit-log.category'), trans('admin/agentpos/general.audit-log.msg-enabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $position = $this->position->find($user_id);
                $position->is_active = 1;
                $position->save();
            }
            Flash::success(trans('admin/agentpos/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('admin/agentpos/general.status.no-agent-position-selected'));
        }
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/agentpos/general.audit-log.category'), trans('admin/agentpos/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $position = $this->position->find($user_id);
                $position->is_active = 0;
                $position->save();
            }
            Flash::success(trans('admin/agentpos/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('admin/agentpos/general.status.no-agent-position-selected'));
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO: implement!
    }
}
