<?php

namespace App\Http\Controllers;

use App\Http\Requests\MGIRequest;
use File;
use Illuminate\Http\Request;
use App\Repositories\AuditRepository as Audit;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Auth;
use Nayjest\Grids\EloquentDataProvider;

class MGISettingController extends Controller
{

    public function index($enabledOnly = 1)
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/mgi/general.audit-log.category'), trans('admin/mgi/general.audit-log.msg-index'));

        $page_title = trans('admin/mgi/general.page.index.title');
        $page_description = trans('admin/mgi/general.page.index.description');

        $data = config('MGIs');

        return view('admin.mgi.index', compact('data', 'page_title', 'page_description', 'enabledOnly'));
    }

    public function create()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/mgi/general.audit-log.category'), trans('admin/mgi/general.audit-log.msg-create'));

        $page_title = trans('admin/mgi/general.page.create.title');
        $page_description = trans('admin/mgi/general.page.create.description');

        return view('admin.mgi.create', compact('page_title', 'page_description'));
    }

    public function store(MGIRequest $request)
    {
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/mgi/general.audit-log.category'), trans('admin/mgi/general.audit-log.msg-store', ['code' => $attributes['code']]), $attributes);

        $MGIs = config('MGIs');
        if (array_key_exists($attributes['code'], $MGIs)){
            //failed
            Flash::error('MGI Code exist. Please type another Code or edit the existing MGI.');
            return redirect()->back()->withInput();
        }

        $MGIs[$attributes['code']] = [$attributes['name'], $attributes['month']];
        $dataToBeSaved = var_export($MGIs, 1);
        if(File::put(base_path() . '/config/MGIs.php', "<?php\n return $dataToBeSaved ;")) {
            Flash::success( trans('admin/mgi/general.audit-log.msg-store', ['code' => $attributes['code']]) );

            return redirect('/admin/mgi');
        }

        Flash::error('Something went wrong. Please contact the administrator.');
        return redirect()->back()->withInput();
    }

    public function edit($code)
    {
        if(array_key_exists($code, config('MGIs'))){

            Audit::log(Auth::user()->id, request()->ip(), trans('admin/mgi/general.audit-log.category'), trans('admin/mgi/general.audit-log.msg-edit', ['code' => $code]));

            $page_title = trans('admin/mgi/general.page.edit.title');
            $page_description = trans('admin/mgi/general.page.edit.description', ['code' => $code]);

            $mgi = config('MGIs.' . $code);
            return view('admin.mgi.edit', compact('mgi', 'code', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('admin/mgi/general.error.no-data') );
            return redirect('admin/mgi');
        }
    }

    public function update(MGIRequest $request)
    {
        $attributes = $request->all();

        $MGIs = config('MGIs');
        if (array_key_exists($attributes['code'], $MGIs)){
            $MGIs[$attributes['code']] = [$attributes['name'], $attributes['month']];
            $dataToBeSaved = var_export($MGIs, 1);
            if(File::put(base_path() . '/config/MGIs.php', "<?php\n return $dataToBeSaved ;")) {
                Audit::log(Auth::user()->id, request()->ip(), trans('admin/mgi/general.audit-log.category'), trans('admin/mgi/general.audit-log.msg-update', ['code' => $attributes['code']]), $attributes);

                Flash::success( trans('admin/mgi/general.audit-log.msg-update', ['code' => $attributes['code']]) );
                return redirect('/admin/mgi');
            }
        }

        Flash::error('MGI Code doesn\'t exists.');
        return redirect('admin/mgi');
    }


    public function delete($code)
    {
        $MGIs = config('MGIs');
        if (array_key_exists($code, $MGIs)){
            $dataToBeDeleted = [$code => $MGIs[$code]];
            unset($MGIs[$code]);

            $dataToBeSaved = var_export($MGIs, 1);
            if(File::put(base_path() . '/config/MGIs.php', "<?php\n return $dataToBeSaved ;")) {
                Audit::log(Auth::user()->id, request()->ip(), trans('admin/mgi/general.audit-log.category'), trans('admin/mgi/general.audit-log.msg-delete', ['code' => $code]), $dataToBeDeleted);

                Flash::success(trans('admin/mgi/general.status.deleted'));
                return redirect('admin/mgi');
            }
        }

        Flash::error('MGI Code doesn\'t exists.');
        return redirect('admin/mgi');
    }

}
