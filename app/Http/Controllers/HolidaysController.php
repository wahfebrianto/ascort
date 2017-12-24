<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\HolidayRequest;
use App\Http\Controllers\Controller;
use App\Holiday;
use App\Repositories\AuditRepository as Audit;
use Flash;
use Auth;
use Nayjest\Grids\EloquentDataProvider;

class HolidaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/holidays/general.audit-log.category'), trans('admin/holidays/general.audit-log.msg-index'));

        $page_title = trans('admin/holidays/general.page.index.title');
        $page_description = trans('admin/holidays/general.page.index.description');

        $dataProvider = new EloquentDataProvider(Holiday::query());

        return view('admin.holidays.index', compact('dataProvider', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/holidays/general.page.create.title');
        $page_description = trans('admin/holidays/general.page.create.description');

        return view('admin.holidays.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\HolidayRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(HolidayRequest $request)
    {
        $attributes = $request->all();

        $new = Holiday::create($attributes);
        $new->year = \Carbon\Carbon::createFromFormat('d/m/Y', $new->date)->year;
        $new->save($attributes);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/holidays/general.audit-log.category'), trans('admin/holidays/general.audit-log.msg-store', ['ID' => $new['id']]), $attributes);
        Flash::success( trans('admin/holidays/general.audit-log.msg-store', ['ID' => $new['id']]) );

        return redirect('/admin/holidays/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $holiday = Holiday::where('id', '=', $id)->first();
        if($holiday != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('admin/holidays/general.audit-log.category'), trans('admin/holidays/general.audit-log.msg-show', ['ID' => $holiday->id]));

            $page_title = trans('admin/holidays/general.page.show.title');
            $page_description = trans('admin/holidays/general.page.show.description', ['ID' => $holiday->id]);

            return view('admin.holidays.show', compact('holiday', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('admin/holidays/general.error.no-data') );
            return redirect('admin/holidays/');
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
        $holiday = Holiday::where('id', '=', $id)->first();
        if($holiday != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('admin/holidays/general.audit-log.category'), trans('admin/holidays/general.audit-log.msg-edit', ['ID' => $holiday->id]));

            $page_title = trans('admin/holidays/general.page.edit.title');
            $page_description = trans('admin/holidays/general.page.edit.description', ['ID' => $holiday->id]);

            return view('admin.holidays.edit', compact('holiday', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('admin/holidays/general.error.no-data') );
            return redirect('admin/holidays/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\HolidayRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\HolidayRequest $request, $id)
    {
        $holiday = Holiday::find($id);
        $attributes = $request->all();
        $attributes['year'] = Carbon::createFromFormat('d/m/Y', $attributes['date'])->year;

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/holidays/general.audit-log.category'), trans('admin/holidays/general.audit-log.msg-update', ['ID' => $attributes['id']]), $holiday->toArray());

        $holiday->update($attributes);
        Flash::success( trans('admin/holidays/general.audit-log.msg-update', ['ID' => $attributes['id']]) );
        return redirect()->route('admin.holidays.edit', ['id' => $id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $holiday = Holiday::find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/holidays/general.audit-log.category'), trans('admin/holidays/general.audit-log.msg-disabled', ['ID' => $id]), $holiday->toArray());

        $holiday->delete();

        Flash::success(trans('admin/holidays/general.status.disabled'));
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');
        $arr_holiday = [];

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $holiday = Holiday::find($user_id);
                $arr_holiday[] = $holiday;
                $holiday->delete();
            }
            Flash::success(trans('admin/holidays/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('admin/holidays/general.status.no-commission-selected'));
        }

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/holidays/general.audit-log.category'), trans('admin/holidays/general.audit-log.msg-disabled-selected'), $arr_holiday);
        return redirect()->back();
    }

    public function load()
    {
        Holiday::loadFromGoogleCalendar();
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/holidays/general.audit-log.category'), trans('admin/holidays/general.audit-log.msg-loaded'));
        Flash::success(trans('admin/holidays/general.status.global-disabled'));
        return redirect()->back();
    }
}
