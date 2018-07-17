<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateBoardOfDirectorRequest;
use App\Http\Requests\EditBoardOfDirectorRequest;
use App\Repositories\AuditRepository as Audit;
use App\BoardOfDirector;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Auth;
use Nayjest\Grids\EloquentDataProvider;

class BoardOfDirectorController extends Controller
{

    /**
     * @var Product
     */
    protected $boardofdirector;

    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enabledOnly = 1)
    {

        Audit::log(Auth::user()->id, request()->ip(), trans('boardofdirector/general.audit-log.category'), trans('boardofdirector/general.audit-log.msg-index'));

        $page_title = trans('boardofdirector/general.page.index.title');
        $page_description = trans('boardofdirector/general.page.index.description');

        $dataProvider = new EloquentDataProvider(BoardOfDirector::query()->where('is_active', $enabledOnly));
        return view('admin.boardofdirector.index', compact('dataProvider', 'page_title', 'page_description', 'enabledOnly'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('boardofdirector/general.audit-log.category'), trans('boardofdirector/general.audit-log.msg-create'));

        $page_title = trans('boardofdirector/general.page.create.title');
        $page_description = trans('boardofdirector/general.page.create.description');

        $boardofdirector = new BoardOfDirector();

        return view('admin.boardofdirector.create', compact('boardofdirector', 'page_title', 'page_description'));
    }

    public function store(CreateBoardOfDirectorRequest $request)
    {

        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('boardofdirector/general.audit-log.category'), trans('boardofdirector/general.audit-log.msg-store', ['bod_name' => $attributes['bod_name']]), $attributes);

        // prepare newProduct, Product instance
        
        $newBoardOfDirector = BoardOfDirector::create($attributes);
        $newBoardOfDirector->save($attributes);
        Flash::success( trans('boardofdirector/general.audit-log.msg-store', ['name' => $attributes['bod_name']]) );

        return redirect('/admin/boardofdirector/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boardOfDirector = BranchOffice::getBranchOfficeFromId($id);
        if($boardOfDirector != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('boardofdirector/general.audit-log.category'), trans('boardofdirector/general.audit-log.msg-show', ['ID' => $boardOfDirector->id]), $boardOfDirector->toArray());

            $page_title = trans('boardofdirector/general.page.show.title');
            $page_description = trans('boardofdirector/general.page.show.description', ['name' => $boardOfDirector->branch_name]);

            return view('admin.branchoffice.show', compact('branchOffice', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('boardofdirector/general.error.no-data') );
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
        $boardOfDirector= BoardOfDirector::find($id);

        if($boardOfDirector != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('boardofdirector/general.audit-log.category'), trans('boardofdirector/general.audit-log.msg-edit', ['ID' => $boardOfDirector->id]));

            $page_title = trans('boardofdirector/general.page.edit.title');
            $page_description = trans('boardofdirector/general.page.edit.description', ['name' => $boardOfDirector->bod_name]);

            return view('admin.boardofdirector.edit', compact('boardOfDirector', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('boardofdirector/general.error.no-data') );
            return redirect('/admin/boardofdirector/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditBranchOfficeRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditBoardOfDirectorRequest $request, $id)
    {
        $boardOfDirector = BoardOfDirector::find($id);
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('boardofdirector/general.audit-log.category'), trans('boardofdirector/general.audit-log.msg-update', ['ID' => $attributes['id']]), $boardOfDirector->toArray());

        $boardOfDirector->update($attributes);
        Flash::success( trans('boardofdirector/general.audit-log.msg-update', ['ID' => $attributes['id']]) );
        return redirect()->route('admin.boardofdirector.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $boardOfDirector = BoardOfDirector::find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('boardofdirector/general.audit-log.category'), trans('boardofdirector/general.audit-log.msg-enable', ['ID' => $id]), $boardOfDirector->toArray());

        $boardOfDirector->is_active = 1;
        $boardOfDirector->save();

        Flash::success(trans('boardofdirector/general.status.enabled'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $boardOfDirector = BoardOfDirector::find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('boardofdirector/general.audit-log.category'), trans('boardofdirector/general.audit-log.msg-disabled', ['ID' => $id]), $boardOfDirector->toArray());

        $boardOfDirector->is_active = 0;
        $boardOfDirector->save();

        Flash::success(trans('boardofdirector/general.status.disabled'));
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkUsers = $request->input('chkBOD');

        Audit::log(Auth::user()->id, request()->ip(), trans('boardofdirector/general.audit-log.category'), trans('boardofdirector/general.audit-log.msg-enabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $boardOfDirector = BoardOfDirector::find($user_id);
                $boardOfDirector->is_active = 1;
                $boardOfDirector->save();
            }
            Flash::success(trans('boardofdirector/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('boardofdirector/general.status.no-product-selected'));
        }
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkBOD');

        Audit::log(Auth::user()->id, request()->ip(), trans('boardofdirector/general.audit-log.category'), trans('boardofdirector/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $boardOfDirector = BoardOfDirector::find($user_id);
                $boardOfDirector->is_active = 0;
                $boardOfDirector->save();
            }
            Flash::success(trans('boardofdirector/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('boardofdirector/general.status.no-product-selected'));
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
