<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSaleCommissionRequest;
use App\Http\Requests\EditSaleCommissionRequest;
use App\Repositories\AuditRepository as Audit;
use App\SaleCommissionPercentage;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Auth;
use Nayjest\Grids\EloquentDataProvider;

class SaleCommissionsController extends Controller
{

    /**
     * @var SaleCommissionPercentage
     */
    protected $commission;

    public function __construct(SaleCommissionPercentage $commission)
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
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/salecommission/general.audit-log.category'), trans('admin/salecommission/general.audit-log.msg-index'));

        $page_title = trans('admin/salecommission/general.page.index.title');
        $page_description = trans('admin/salecommission/general.page.index.description');

        $dataProvider = new EloquentDataProvider(SaleCommissionPercentage::query()->with('agent_position')->where('is_active', $enabledOnly));

        return view('admin.salecommission.index', compact('dataProvider', 'page_title', 'page_description', 'enabledOnly'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/salecommission/general.audit-log.category'), trans('admin/salecommission/general.audit-log.msg-create'));

        $page_title = trans('admin/salecommission/general.page.create.title');
        $page_description = trans('admin/salecommission/general.page.create.description');

        $commission = new SaleCommissionPercentage();

        return view('admin.salecommission.create', compact('commission', 'page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateSaleCommissionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSaleCommissionRequest $request)
    {
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/salecommission/general.audit-log.category'), trans('admin/salecommission/general.audit-log.msg-store', ['ID' => $attributes['id']]));

        // prepare newSaleCommissionPercentage, SaleCommissionPercentage instance
        $newSaleCommissionPercentage = $this->commission->create($attributes);
        $newSaleCommissionPercentage->save($attributes);
        Flash::success( trans('admin/salecommission/general.audit-log.msg-store', ['ID' => $attributes['id']]) );

        return redirect('/admin/salecommission/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commission = \App\SaleCommissionPercentage::getSaleCommissionPercentageFromId($id);
        if($commission != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('admin/salecommission/general.audit-log.category'), trans('admin/salecommission/general.audit-log.msg-show', ['ID' => $commission->id]));

            $page_title = trans('admin/salecommission/general.page.show.title');
            $page_description = trans('admin/salecommission/general.page.show.description', ['ID' => $commission->id]);

            return view('admin.salecommission.show', compact('commission', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('admin/salecommission/general.error.no-data') );
            return redirect('admin/salecommission/');
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
        $commission = \App\SaleCommissionPercentage::getSaleCommissionPercentageFromId($id);
        if($commission != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('admin/salecommission/general.audit-log.category'), trans('admin/salecommission/general.audit-log.msg-edit', ['ID' => $commission->id]));

            $page_title = trans('admin/salecommission/general.page.edit.title');
            $page_description = trans('admin/salecommission/general.page.edit.description', ['ID' => $commission->id]);

            return view('admin.salecommission.edit', compact('commission', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('admin/salecommission/general.error.no-data') );
            return redirect('admin/salecommission/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditSaleCommissionRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditSaleCommissionRequest $request, $id)
    {
        $commission = $this->commission->find($id);
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/salecommission/general.audit-log.category'), trans('admin/salecommission/general.audit-log.msg-update', ['ID' => $attributes['id']]));

        $commission->update($attributes);
        Flash::success( trans('admin/salecommission/general.audit-log.msg-update', ['ID' => $attributes['id']]) );
        return redirect()->route('admin.salecommission.edit', ['id' => $id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $commission = $this->commission->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/salecommission/general.audit-log.category'), trans('admin/salecommission/general.audit-log.msg-enable', ['ID' => $id]));

        $commission->is_active = 1;
        $commission->save();

        Flash::success(trans('admin/salecommission/general.status.enabled'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $commission = $this->commission->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/salecommission/general.audit-log.category'), trans('admin/salecommission/general.audit-log.msg-disabled', ['ID' => $id]));

        $commission->is_active = 0;
        $commission->save();

        Flash::success(trans('admin/salecommission/general.status.disabled'));
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/salecommission/general.audit-log.category'), trans('admin/salecommission/general.audit-log.msg-enabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $commission = $this->commission->find($user_id);
                $commission->is_active = 1;
                $commission->save();
            }
            Flash::success(trans('admin/salecommission/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('admin/salecommission/general.status.no-commission-selected'));
        }
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/salecommission/general.audit-log.category'), trans('admin/salecommission/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $commission = $this->commission->find($user_id);
                $commission->is_active = 0;
                $commission->save();
            }
            Flash::success(trans('admin/salecommission/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('admin/salecommission/general.status.no-commission-selected'));
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
