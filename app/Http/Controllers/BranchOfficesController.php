<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBranchOfficeRequest;
use App\Http\Requests\EditBranchOfficeRequest;
use App\Repositories\AuditRepository as Audit;
use App\BranchOffice;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Auth;
use Nayjest\Grids\EloquentDataProvider;

class BranchOfficesController extends Controller
{

    /**
     * @var Product
     */
    protected $branchOffice;

    public function __construct(BranchOffice $branchOffice)
    {
        $this->$branchOffice  = $branchOffice;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enabledOnly = 1)
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('branch_office/general.audit-log.category'), trans('branch_office/general.audit-log.msg-index'));

        $page_title = trans('branch_office/general.page.index.title');
        $page_description = trans('branch_office/general.page.index.description');

        $dataProvider = new EloquentDataProvider(BranchOffice::query()->where('is_active', $enabledOnly));
        return view('admin.branchoffice.index', compact('dataProvider', 'page_title', 'page_description', 'enabledOnly'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('branch_office/general.audit-log.category'), trans('branch_office/general.audit-log.msg-create'));

        $page_title = trans('branch_office/general.page.create.title');
        $page_description = trans('branch_office/general.page.create.description');

        $branchOffice = new BranchOffice();

        return view('admin.branchoffice.create', compact('branchOffice', 'page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateBranchOfficeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBranchOfficeRequest $request)
    {
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('branch_office/general.audit-log.category'), trans('branch_office/general.audit-log.msg-store', ['branch_name' => $attributes['branch_name']]), $attributes);

        // prepare newProduct, Product instance
        $newBranchOffice = BranchOffice::create($attributes);
        $newBranchOffice->save($attributes);
        Flash::success( trans('branch_office/general.audit-log.msg-store', ['branch_name' => $attributes['branch_name']]) );

        return redirect('/admin/branchoffice/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $branchOffice = BranchOffice::getBranchOfficeFromId($id);
        if($branchOffice != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('branch_office/general.audit-log.category'), trans('branch_office/general.audit-log.msg-show', ['ID' => $branchOffice->id]), $branchOffice->toArray());

            $page_title = trans('branch_office/general.page.show.title');
            $page_description = trans('branch_office/general.page.show.description', ['name' => $branchOffice->branch_name]);

            return view('admin.branchoffice.show', compact('branchOffice', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('branch_office/general.error.no-data') );
            return redirect('/admin/branchoffice/');
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
        $branchOffice = BranchOffice::getBranchOfficeFromId($id);
        if($branchOffice != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('branch_office/general.audit-log.category'), trans('branch_office/general.audit-log.msg-edit', ['ID' => $branchOffice->id]));

            $page_title = trans('branch_office/general.page.edit.title');
            $page_description = trans('branch_office/general.page.edit.description', ['name' => $branchOffice->branch_name]);

            return view('admin.branchoffice.edit', compact('branchOffice', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('branch_office/general.error.no-data') );
            return redirect('/admin/branchoffice/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditBranchOfficeRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditBranchOfficeRequest $request, $id)
    {
        $branchOffice = BranchOffice::find($id);
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('branch_office/general.audit-log.category'), trans('branch_office/general.audit-log.msg-update', ['ID' => $attributes['id']]), $branchOffice->toArray());

        $branchOffice->update($attributes);
        Flash::success( trans('branch_office/general.audit-log.msg-update', ['ID' => $attributes['id']]) );
        return redirect()->route('admin.branchoffice.edit', ['id' => $id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $branchOffice = BranchOffice::find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('branch_office/general.audit-log.category'), trans('branch_office/general.audit-log.msg-enable', ['ID' => $id]), $branchOffice->toArray());

        $branchOffice->is_active = 1;
        $branchOffice->save();

        Flash::success(trans('branch_office/general.status.enabled'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $branchOffice = BranchOffice::find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('branch_office/general.audit-log.category'), trans('branch_office/general.audit-log.msg-disabled', ['ID' => $id]), $branchOffice->toArray());

        $branchOffice->is_active = 0;
        $branchOffice->save();

        Flash::success(trans('branch_office/general.status.disabled'));
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('branch_office/general.audit-log.category'), trans('branch_office/general.audit-log.msg-enabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $branchOffice = $this->branchOffice->find($user_id);
                $branchOffice->is_active = 1;
                $branchOffice->save();
            }
            Flash::success(trans('branch_office/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('branch_office/general.status.no-product-selected'));
        }
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('branch_office/general.audit-log.category'), trans('branch_office/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $branchOffice = $this->branchOffice->find($user_id);
                $branchOffice->is_active = 0;
                $branchOffice->save();
            }
            Flash::success(trans('branch_office/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('branch_office/general.status.no-product-selected'));
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
