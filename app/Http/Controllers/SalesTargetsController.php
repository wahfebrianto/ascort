<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSalesTargetRequest;
use App\Http\Requests\EditSalesTargetRequest;
use App\Repositories\AuditRepository as Audit;
use App\SalesTarget;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Auth;
use Nayjest\Grids\EloquentDataProvider;

class SalesTargetsController extends Controller
{

    /**
     * @var SalesTarget
     */
    protected $target;

    public function __construct(SalesTarget $target)
    {
        $this->target  = $target;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enabledOnly = 1)
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/target/general.audit-log.category'), trans('admin/target/general.audit-log.msg-index'));

        $page_title = trans('admin/target/general.page.index.title');
        $page_description = trans('admin/target/general.page.index.description');

        $dataProvider = new EloquentDataProvider(SalesTarget::with('agent_position'));
        return view('admin.target.index', compact('dataProvider', 'page_title', 'page_description', 'enabledOnly'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/target/general.audit-log.category'), trans('admin/target/general.audit-log.msg-create'));

        $page_title = trans('admin/target/general.page.create.title');
        $page_description = trans('admin/target/general.page.create.description');

        $target = new SalesTarget();

        return view('admin.target.create', compact('target', 'page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateSalesTargetRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSalesTargetRequest $request)
    {
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/target/general.audit-log.category'), trans('admin/target/general.audit-log.msg-store', ['id' => $attributes['id']]), $attributes);

        // prepare newSalesTarget, SalesTarget instance
        $newSalesTarget = $this->target->create($attributes);
        $newSalesTarget->save($attributes);
        Flash::success( trans('admin/target/general.audit-log.msg-store', ['id' => $attributes['id']]) );

        return redirect('/admin/target/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $target = SalesTarget::getSalesTargetFromId($id);
        if($target != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('admin/target/general.audit-log.category'), trans('admin/target/general.audit-log.msg-show', ['ID' => $target->id]));

            $page_title = trans('admin/target/general.page.show.title');
            $page_description = trans('admin/target/general.page.show.description', ['id' => $target->id]);

            return view('admin.target.show', compact('target', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('admin/target/general.error.no-data') );
            return redirect('admin/target/');
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
        $target = SalesTarget::getSalesTargetFromId($id);
        if($target != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('admin/target/general.audit-log.category'), trans('admin/target/general.audit-log.msg-edit', ['id' => $target->id]), $target->toArray());

            $page_title = trans('admin/target/general.page.edit.title');
            $page_description = trans('admin/target/general.page.edit.description', ['id' => $target->id]);

            return view('admin.target.edit', compact('target', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('admin/target/general.error.no-data') );
            return redirect('admin/target/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditSalesTargetRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditSalesTargetRequest $request, $id)
    {
        $target = $this->target->find($id);
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/target/general.audit-log.category'), trans('admin/target/general.audit-log.msg-update', ['ID' => $attributes['id']]));

        $target->update($attributes);
        Flash::success( trans('admin/target/general.audit-log.msg-update', ['id' => $attributes['id']]) );
        return redirect()->route('admin.target.edit', ['id' => $id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $target = $this->target->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/target/general.audit-log.category'), trans('admin/target/general.audit-log.msg-enable', ['ID' => $id]), $target->toArray());

        $target->is_active = 1;
        $target->save();

        Flash::success(trans('admin/target/general.status.enabled'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $target = $this->target->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/target/general.audit-log.category'), trans('admin/target/general.audit-log.msg-disabled', ['ID' => $id]), $target->toArray());

        $target->is_active = 0;
        $target->save();

        Flash::success(trans('admin/target/general.status.disabled'));
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/target/general.audit-log.category'), trans('admin/target/general.audit-log.msg-enabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $target = $this->target->find($user_id);
                $target->is_active = 1;
                $target->save();
            }
            Flash::success(trans('admin/target/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('admin/target/general.status.no-sales-target-selected'));
        }
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/target/general.audit-log.category'), trans('admin/target/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $target = $this->target->find($user_id);
                $target->is_active = 0;
                $target->save();
            }
            Flash::success(trans('admin/target/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('admin/target/general.status.no-sales-target-selected'));
        }
        return redirect()->back();
    }
}
