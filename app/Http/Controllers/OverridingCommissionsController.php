<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOverridingCommissionRequest;
use App\Http\Requests\EditOverridingCommissionRequest;
use App\Repositories\AuditRepository as Audit;
use App\OverridingCommissionPercentage;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Auth;
use Nayjest\Grids\EloquentDataProvider;

class OverridingCommissionsController extends Controller
{

    /**
     * @var OverridingCommissionPercentage
     */
    protected $commission;

    public function __construct(OverridingCommissionPercentage $commission)
    {
        $this->commission  = $commission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enabledOnly = 1)
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/ovrcommission/general.audit-log.category'), trans('admin/ovrcommission/general.audit-log.msg-index'));

        $page_title = trans('admin/ovrcommission/general.page.index.title');
        $page_description = trans('admin/ovrcommission/general.page.index.description');

        $dataProvider = new EloquentDataProvider(OverridingCommissionPercentage::query()->with('agent_position')->with('override_from_agent')->where('is_active', $enabledOnly));

        return view('admin.ovrcommission.index', compact('dataProvider', 'page_title', 'page_description', 'enabledOnly'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/ovrcommission/general.audit-log.category'), trans('admin/ovrcommission/general.audit-log.msg-create'));

        $page_title = trans('admin/ovrcommission/general.page.create.title');
        $page_description = trans('admin/ovrcommission/general.page.create.description');

        $commission = new OverridingCommissionPercentage();

        return view('admin.ovrcommission.create', compact('commission', 'page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateOverridingCommissionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOverridingCommissionRequest $request)
    {
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/ovrcommission/general.audit-log.category'), trans('admin/ovrcommission/general.audit-log.msg-store', ['ID' => $attributes['id']]), $attributes);

        // prepare newOverridingCommissionPercentage, OverridingCommissionPercentage instance
        $newOverridingCommissionPercentage = $this->commission->create($attributes);
        $newOverridingCommissionPercentage->save($attributes);
        Flash::success( trans('admin/ovrcommission/general.audit-log.msg-store', ['ID' => $attributes['id']]) );

        return redirect('/admin/ovrcommission/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commission = \App\OverridingCommissionPercentage::getOverridingCommissionPercentageFromId($id);
        if($commission != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('admin/ovrcommission/general.audit-log.category'), trans('admin/ovrcommission/general.audit-log.msg-show', ['ID' => $commission->id]));

            $page_title = trans('admin/ovrcommission/general.page.show.title');
            $page_description = trans('admin/ovrcommission/general.page.show.description', ['ID' => $commission->id]);

            return view('admin.ovrcommission.show', compact('commission', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('admin/ovrcommission/general.error.no-data') );
            return redirect('admin/ovrcommission/');
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
        $commission = \App\OverridingCommissionPercentage::getOverridingCommissionPercentageFromId($id);
        if($commission != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('admin/ovrcommission/general.audit-log.category'), trans('admin/ovrcommission/general.audit-log.msg-edit', ['ID' => $commission->id]));

            $page_title = trans('admin/ovrcommission/general.page.edit.title');
            $page_description = trans('admin/ovrcommission/general.page.edit.description', ['ID' => $commission->id]);

            return view('admin.ovrcommission.edit', compact('commission', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('admin/ovrcommission/general.error.no-data') );
            return redirect('admin/ovrcommission/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditOverridingCommissionRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditOverridingCommissionRequest $request, $id)
    {
        $commission = $this->commission->find($id);
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/ovrcommission/general.audit-log.category'), trans('admin/ovrcommission/general.audit-log.msg-update', ['ID' => $attributes['id']]), $commission->toArray());

        $commission->update($attributes);
        Flash::success( trans('admin/ovrcommission/general.audit-log.msg-update', ['ID' => $attributes['id']]) );
        return redirect()->route('admin.ovrcommission.edit', ['id' => $id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $commission = $this->commission->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/ovrcommission/general.audit-log.category'), trans('admin/ovrcommission/general.audit-log.msg-enable', ['ID' => $id]), $commission->toArray());

        $commission->is_active = 1;
        $commission->save();

        Flash::success(trans('admin/ovrcommission/general.status.enabled'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $commission = $this->commission->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/ovrcommission/general.audit-log.category'), trans('admin/ovrcommission/general.audit-log.msg-disabled', ['ID' => $id]), $commission->toArray());

        $commission->is_active = 0;
        $commission->save();

        Flash::success(trans('admin/ovrcommission/general.status.disabled'));
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/ovrcommission/general.audit-log.category'), trans('admin/ovrcommission/general.audit-log.msg-enabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $commission = $this->commission->find($user_id);
                $commission->is_active = 1;
                $commission->save();
            }
            Flash::success(trans('admin/ovrcommission/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('admin/ovrcommission/general.status.no-commission-selected'));
        }
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/ovrcommission/general.audit-log.category'), trans('admin/ovrcommission/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $commission = $this->commission->find($user_id);
                $commission->is_active = 0;
                $commission->save();
            }
            Flash::success(trans('admin/ovrcommission/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('admin/ovrcommission/general.status.no-commission-selected'));
        }
        return redirect()->back();
    }
}
