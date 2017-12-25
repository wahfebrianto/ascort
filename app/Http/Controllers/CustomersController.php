<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\EditCustomerRequest;
use App\Http\Controllers\Controller;
use App\Repositories\CustomerRepository as Customer;
use App\Repositories\AuditRepository as Audit;
use Nayjest\Grids\EloquentDataProvider;
use App\Http\Requests\CustomersExcelExport;
use Flash;
use Auth;
use Input;
use App\BranchOffice;
use App\Approval;

class CustomersController extends Controller
{

    /**
     * @var Customer
     */
    protected $customer;

    public function __construct(Customer $customer)
    {
        $this->customer  = $customer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enabledOnly = 1)
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('customers/general.audit-log.category'), trans('customers/general.audit-log.msg-index'));

        $page_title = trans('customers/general.page.index.title');
        $page_description = trans('customers/general.page.index.description');

        $modelColumns = \App\Customer::getColumnsArray();
        $dataProvider = new EloquentDataProvider(\App\Customer::query()->where('is_active', $enabledOnly)->whereIn('branch_office_id', \App\BranchOffice::getBranchOfficesID()));
        return view('customers.index', compact('dataProvider', 'modelColumns', 'page_title', 'page_description', 'enabledOnly'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('customers/general.audit-log.category'), trans('customers/general.audit-log.msg-create'));

        $page_title = trans('customers/general.page.create.title');
        $page_description = trans('customers/general.page.create.description');

        $customer = new \App\Customer();

        return view('customers.create', compact('customer', 'page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCustomerRequest $request)
    {
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('customers/general.audit-log.category'), trans('customers/general.audit-log.msg-store', ['NIK' => $attributes['NIK']]), $attributes);

        // prepare newCustomer, Customer instance
        $newCustomer = $this->customer->create($attributes);

        if(Auth::getUser()->hasRole('owner'))
        {
          $newCustomer->is_active = 1;
        }
        else {
          $newCustomer->is_active = 0;
          $branchName = BranchOffice::getBranchOfficeFromId($newCustomer->branch_office_id)->branch_name;
          $custID = $newCustomer->id;
          $custName = $newCustomer->name;
          $approvalAttributes = [];
          $approvalAttributes["user_id"] = Auth::user()->id;
          $approvalAttributes["subject"] = "Add New Customer";
          $approvalAttributes["description"] = "<a href='/customer/".$custID."''>".$custID."-".$custName."</a> ($branchName)";
          $approvalAttributes["is_approved"] = 0;
          $newApproval = Approval::create($approvalAttributes);
          $newApproval->save($approvalAttributes);
        }
        $newCustomer->save($attributes);
        Flash::success( trans('customers/general.audit-log.msg-store', ['NIK' => $attributes['NIK']]) );

        if(Auth::getUser()->hasRole('admin')) return redirect('/customers/create');
        return redirect('/customers/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = \App\Customer::getCustomerFromId($id);
        if($customer != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('customers/general.audit-log.category'), trans('customers/general.audit-log.msg-show', ['ID' => $customer->id]));

            $page_title = trans('customers/general.page.show.title');
            $page_description = trans('customers/general.page.show.description', ['name' => $customer->name]);

            $sales_provider = new EloquentDataProvider(\App\Sale::with('agent')->where('customer_id', '=', $id));

            return view('customers.show', compact('customer', 'sales_provider', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('customers/general.error.no-data') );
            return redirect('customers/');
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
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner') || Auth::user()->hasRole('super-admin')) {
            if(!Input::has('token') || Input::get('token') != md5(\Session::getId() . 'customer')) {
                \App::abort(403, 'Access denied');
            } else {
                $id = Input::get('id');
            }
        }

        $customer = \App\Customer::getCustomerFromId($id);
        if($customer != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('customers/general.audit-log.category'), trans('customers/general.audit-log.msg-edit', ['ID' => $customer->id]));

            $page_title = trans('customers/general.page.edit.title');
            $page_description = trans('customers/general.page.edit.description', ['name' => $customer->name]);

            return view('customers.edit', compact('customer', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('customers/general.error.no-data') );
            return redirect('customers/');
        }
    }

    public function admin_edit() {
        $page_title = trans('customers/general.page.admin_edit.title');
        $page_description = trans('customers/general.page.admin_edit.description');

        $customer_lists = \App\Customer::isActive()->whereIn('branch_office_id', \App\BranchOffice::getBranchOfficesID())->lists('NIK', 'id');
        return view('customers.admin_edit', compact('customer_lists', 'page_title', 'page_description'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditCustomerRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditCustomerRequest $request, $id)
    {
        $customer = $this->customer->find($id);
        $oldFilename = $customer->id_card_image_filename;

        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('customers/general.audit-log.category'), trans('customers/general.audit-log.msg-update', ['ID' => $attributes['id']]), $customer->toArray());

        $customer->update($attributes);
        Flash::success( trans('customers/general.audit-log.msg-update', ['ID' => $attributes['id']]) );

        if(Auth::getUser()->hasRole('admin')) return redirect('/customers/admin_edit');
        return redirect()->route('customers.edit', ['id' => $id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $customer = $this->customer->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('customers/general.audit-log.category'), trans('customers/general.audit-log.msg-enable', ['ID' => $id]), $customer->toArray());

        $customer->is_active = 1;
        $customer->save();

        Flash::success(trans('customers/general.status.enabled'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $customer = $this->customer->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('customers/general.audit-log.category'), trans('customers/general.audit-log.msg-disabled', ['ID' => $id]), $customer->toArray());

        $customer->is_active = 0;
        $customer->save();

        Flash::success(trans('customers/general.status.disabled'));
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('customers/general.audit-log.category'), trans('customers/general.audit-log.msg-enabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $customer = $this->customer->find($user_id);
                $customer->is_active = 1;
                $customer->save();
            }
            Flash::success(trans('customers/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('customers/general.status.no-customer-selected'));
        }
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('customers/general.audit-log.category'), trans('customers/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $customer = $this->customer->find($user_id);
                $customer->is_active = 0;
                $customer->save();
            }
            Flash::success(trans('customers/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('customers/general.status.no-customer-selected'));
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

    public function export(CustomersExcelExport $export)
    {
        // TODO: change pdf generator to manual attribute printing, get agent position name
        if(Input::has('type')) {
            $builder = \App\Customer::where('is_active', 1);

            if(Input::has('created_at_filter1') && Input::has('created_at_filter2')) {
                // apply join date filter
                /*$builder->whereBetween('created_at', [
                    \DateTime::createFromFormat('d/m/Y', Input::get('created_at_filter1')),
                    \DateTime::createFromFormat('d/m/Y', Input::get('created_at_filter2'))
                ]);*/
				$builder->whereBetween('created_at',[date_format(date_create(Input::get('created_at_filter1')),'d/m/Y'),date_format(date_create(Input::get('created_at_filter2')),'d/m/Y')]);
            }
            if(Input::has('chkExp')) {
                // apply checkbox column filter
                $builder->select(Input::get('chkExp'));
            }

            $data = $builder->whereIn('branch_office_id',\App\BranchOffice::getBranchOfficesID())->get();
            $columns = Input::get('chkExp');
            if($data->count() == 0) {
                Flash::error( trans('customers/general.error.no-data') );
                return redirect()->back();
            }
            switch(Input::get('type')) {
                case 'pdf':
                default:
                    $html = \View::make('pdf.customers', compact('data', 'columns', 'enabledOnly'))->render();
                    // $html = str_replace('id=', 'class=', $html); // DOMPDF workaround -> https://github.com/barryvdh/laravel-dompdf/issues/96
                    // $pdf = \App::make('dompdf.wrapper');
                    // $pdf->loadHtml($html);
                    // $pdf->setPaper('A4', 'landscape');
                    // return $pdf->stream('customers.pdf');
					dd($html);
                    $mpdf = new \mPDF("en", "A4-L", "12");
                    $mpdf->WriteHTML($html);
                    return $mpdf->Output();
                case 'xlsx':
                    $dataArray = $data->toArray();
                    $export->data = $dataArray;
                    return $export->handleExport();
            }
        } else {
            Flash::error( trans('customers/general.error.no-data') );
            return redirect()->back();
        }
    }
}
