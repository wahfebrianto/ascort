<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Approval;
use App\Calculations\Interest;
use App\Customer;
use App\BranchOffice;
use App\Http\Requests\CreateSaleRequest;
use App\Http\Requests\EditSaleRequest;
use App\Reminder;
use App\Sale;
use Illuminate\Http\Request;
use App\Repositories\AuditRepository as Audit;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Auth;
use Input;
use Nayjest\Grids\EloquentDataProvider;
use App\Http\Requests\SalesExcelExport;
use Carbon\Carbon;


class SalesController extends Controller
{

    /**
     * @var Sale
     */
    protected $sale;

    public function __construct(Sale $sale)
    {
        $this->sale  = $sale;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enabledOnly = 1)
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-index'));

        $page_title = trans('sales/general.page.index.title');
        $page_description = trans('sales/general.page.index.description');

        $dataProvider = \App\Sale::getIndexDataProvider($enabledOnly);
        $modelColumns = \App\Sale::getColumnsArray();
        return view('sales.index', compact('dataProvider', 'modelColumns', 'page_title', 'page_description', 'enabledOnly'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO: Create new customer in the page
        Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-create'));

        $page_title = trans('sales/general.page.create.title');
        $page_description = trans('sales/general.page.create.description');

        $sale = new Sale();

        $MGIConfig = config('MGIs');
        $MGIs = [];
        $MGIs[null] = "Select MGI";
        foreach($MGIConfig as $key => $value){
            $MGIs[$key] = $value[0];
        }
        return view('sales.create', compact('sale', 'page_title', 'page_description', 'MGIs','branch_offices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateSaleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSaleRequest $request)
    {
        $attributes = $request->all();

        if($request->reminder_id == null){
            //create new

            Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-store', ['number' => $attributes['number']]), $attributes);

            // if(Carbon::createFromFormat('d/m/Y',$attributes['MGI_start_date'])->diffInDays(Carbon::now(), false) >= 10
            //     && Carbon::createFromFormat('d/m/Y',$attributes['MGI_start_date'])->lt(Carbon::now())
            //     && \Auth::user()->hasRole('admin')){
            //     // approval
            //     Approval::create([
            //         'user_id' => Auth::getUser()->id,
            //         'subject' => 'Insert Sale Backdate',
            //         'description' => json_encode([
            //             'attributes' => json_encode($attributes),
            //             'table' => (new Sale())->getTable(),
            //         ]),
            //     ]);
            //
            //     Flash::success('Created new sale with number #' . $attributes['number'] .
            //                     ' with backdate.<br/>Waiting for owner\'s approval.');
            //
            // }else{

                // prepare newSale, Sale instance
                $newSale = $this->sale->create($attributes);
                $newSale->customer_name = Customer::getCustomerFromId($newSale->customer_id)->name;
                $newSale->agent_commission = Sale::getSaleCommissionPercentage($newSale);
				$custid = $newSale->customer_id;

                if(Auth::getUser()->hasRole('owner'))
                {
                  $newSale->is_active = 1;
                }
                else {
                  $newSale->is_active = 2;
                  $branchName = BranchOffice::getBranchOfficeFromId($attributes["branch_office_id"])->branch_name;
                  $approvalAttributes = [];
                  $approvalAttributes["user_id"] = Auth::user()->id;
                  $approvalAttributes["subject"] = "Add New Sales";
                  $approvalAttributes["description"] = "<a href='".route('sales.show', ['id' => $attributes["id"]]).">".$attributes["id"]."-".$attributes["name"]."</a> ($branchName)";
                  $approvalAttributes["is_approved"] = 0;
                  $newApproval = Approval::create($approvalAttributes);
                  $newApproval->save($approvalAttributes);
                }

                $newSale->save($attributes);
				Sale::updateMGIMonth($newSale);
                //Customer::updateLastTransaction(Customer::getCustomerFromId($custid));


                Flash::success( trans('sales/general.audit-log.msg-store', ['number' => $attributes['number']]) );
            // }

        }else{
            //rollover

            Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-store-rollover', ['number' => $attributes['number']]), $attributes);

            // if(Carbon::createFromFormat('d/m/Y',$attributes['MGI_start_date'])->diffInDays(Carbon::now(), false) >= 10
            //     && Carbon::createFromFormat('d/m/Y',$attributes['MGI_start_date'])->lt(Carbon::now())
            //     && \Auth::user()->hasRole('admin')){
            //     // approval
            //     Approval::create([
            //         'user_id' => Auth::getUser()->id,
            //         'subject' => 'Insert Sale Backdate',
            //         'description' => json_encode([
            //             'attributes' => json_encode($attributes),
            //             'table' => (new Sale())->getTable(),
            //         ]),
            //     ]);
            //
            //     Flash::success('Rollover sale with number #' . $attributes['number'] .
            //                     ' with backdate.<br/>Waiting for owner\'s approval.');
            //
            // }else{
                // prepare newSale, Sale instance
                $newSale = $this->sale->create($attributes);

                $newSale->customer_name = Customer::getCustomerFromId($newSale->customer_id)->name;
                $newSale->agent_commission = Sale::getSaleCommissionPercentage($newSale);

                if(Auth::getUser()->hasRole('owner'))
                {
                  $newSale->is_active = 1;
                }
                else {
                  $newSale->is_active = 2;
                  $branchName = BranchOffice::getBranchOfficeFromId($attributes["branch_office_id"])->branch_name;
                  $approvalAttributes = [];
                  $approvalAttributes["user_id"] = Auth::user()->id;
                  $approvalAttributes["subject"] = "Add New Sales";
                  $approvalAttributes["description"] = "<a href='".route('sales.show', ['id' => $attributes["id"]]).">".$attributes["id"]."-".$attributes["name"]."</a> ($branchName)";
                  $newApproval = Approval::create($approvalAttributes);
                  $newApproval->save($approvalAttributes);
                }

                $newSale->save($attributes);

                Sale::updateMGIMonth($newSale);
                Customer::updateLastTransaction(Customer::getCustomerFromId($newSale->customer_id));

                Flash::success( trans('sales/general.audit-log.msg-store-rollover', ['number' => $attributes['number']]) );
            // }

            // dismiss the reminder
            Reminder::inactivateReminder(Reminder::getReminderById($attributes['reminder_id']));

        }
        return redirect('/sales/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = $this->sale->getSaleFromId($id);
        if($sale != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-show', ['ID' => $sale->id]));

            $page_title = trans('sales/general.page.show.title');
            $page_description = trans('sales/general.page.show.description', ['number' => $sale->number]);

            $MGIConfig = config('MGIs');
            $MGIs = [];
            foreach($MGIConfig as $key => $value){
                $MGIs[$key] = $value[0];
            }
			$branchOfficeName = BranchOffice::getBranchOfficeFromId($sale->branch_office_id)->branch_name;
			$customerName = Customer::getCustomerFromId($sale->customer_id)->name;
            return view('sales.show', compact('sale', 'page_title', 'page_description', 'MGIs','branchOfficeName','customerName'));

        }else{
            Flash::error( trans('sales/general.error.no-data') );
            return redirect('sales/');
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
        $sale = $this->sale->getSaleFromId($id);
        if($sale != null && (Auth::getUser()->hasRole('owner') || $sale->is_editable)){

            Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-edit', ['ID' => $sale->id]), $sale->toArray());

            $page_title = trans('sales/general.page.edit.title');
            $page_description = trans('sales/general.page.edit.description', ['name' => $sale->number]);

            $MGIConfig = config('MGIs');
            $MGIs = [];
            foreach($MGIConfig as $key => $value){
                $MGIs[$key] = $value[0];
            }
            $agents = Agent::getAgents_ForDropDown();
            if($sale->agent == null) $agents = ["-" => "None"] + Agent::getAgents_ForDropDown();
            $customers = Customer::getCustomers_ForDropDown();
            if($sale->customer == null) $customers = ["-" => "None"] + Customer::getCustomers_ForDropDown();

            return view('sales.edit', compact('sale', 'agents', 'customers', 'page_title', 'page_description', 'MGIs'));

        } else {
            if($sale == null) {
                Flash::error(trans('sales/general.error.no-data'));
                return redirect('sales/');
            } else {
                Flash::error(trans('sales/general.error.not-editable'));
                return redirect('sales/');
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditSaleRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditSaleRequest $request, $id)
    {
        $sale = $this->sale->find($id);
        if($sale != null && (Auth::getUser()->hasRole('owner') || $sale->is_editable)) {
            $attributes = $request->all();

            $message = trans('sales/general.audit-log.msg-update', ['ID' => $attributes['id']]);
            if(($sale->MGI != $attributes['MGI'] || $sale->MGI_start_date != $attributes['MGI_start_date']
                || $sale->nominal != $attributes['nominal']) && \Auth::user()->hasRole('admin')){
                // need approval

                $contentDesc = [];
                $contentDesc['table'] = (new \App\Sale())->getTable();
                $contentDesc['id'] = $attributes['id'];

                if($sale->MGI != $attributes['MGI'])
                    $contentDesc['MGI'] = $attributes['MGI'];
                if($sale->MGI_start_date != $attributes['MGI_start_date'])
                    $contentDesc['MGI_start_date'] = $attributes['MGI_start_date'];
                if($sale->nominal != $attributes['nominal'])
                    $contentDesc['nominal'] = $attributes['nominal'];

                Approval::create([
                    'user_id' => Auth::getUser()->id,
                    'subject' => 'Change Sales MGI/Nominal',
                    'description' => json_encode($contentDesc),
                ]);

                unset($attributes['MGI']);
                unset($attributes['MGI_start_date']);
                unset($attributes['nominal']);

                $message .= "<br/>Waiting for owner's approval.";
            }

            if($attributes["customer_id"] == "-") unset($attributes["customer_id"]);
            $sale->update($attributes);
            $sale->update([
                'agent_commission' => Sale::getSaleCommissionPercentage($sale),
                'customer_name' => $sale->customer_id == null? "" : Customer::getCustomerFromId($sale->customer_id)->name
            ]);
            Sale::updateMGIMonth($sale);
            Flash::success($message);

        }
        return redirect()->route('sales.edit', ['id' => $id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $sale = $this->sale->find($id);

        if($sale->is_editable) {
            Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-enable', ['ID' => $id]), $sale->toArray());

            $sale->is_active = 1;
            $sale->save();

            Flash::success(trans('sales/general.status.enabled'));
        }

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $sale = $this->sale->find($id);

        if($sale->is_editable) {

            Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-disabled', ['ID' => $id]), $sale->toArray());

            $sale->is_active = 0;
            $sale->save();

            Flash::success(trans('sales/general.status.disabled'));
        }else{
            Flash::error(trans('sales/general.error.not-editable'));
        }
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-enabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $sale = $this->sale->find($user_id);
                if($sale->is_editable) {
                    $sale->is_active = 1;
                    $sale->save();
                }
            }
            Flash::success(trans('sales/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('sales/general.status.no-sale-selected'));
        }
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $sale = $this->sale->find($user_id);

                if($sale->is_editable) {
                    $sale->is_active = 0;
                    $sale->save();
                }
            }
            Flash::success(trans('sales/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('sales/general.status.no-sale-selected'));
        }
        return redirect()->back();
    }

    public function rollover($id, $reminder_id){
        Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-rollover'));

        $page_title = trans('sales/general.page.create.title');
        $page_description = trans('sales/general.page.create.description');

        $oldSale = $this->sale->find($id);

        $sale = new Sale();

        $MGIConfig = config('MGIs');
        $MGIs = [];
        foreach($MGIConfig as $key => $value){
            $MGIs[$key] = $value[0];
        }
        return view('sales.rollover', compact('sale', 'oldSale', 'reminder_id', 'page_title', 'page_description', 'MGIs'));
    }

    public function interest($id){
        $sale = $this->sale->find($id);

        $interests = Interest::getInterest($sale);

        $html = \View::make('pdf.sales_interest', compact('sale', 'interests'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHtml($html);
        $pdf->setPaper('A4');
        $pdf->setOrientation('portrait');
        return $pdf->stream();
    }

    public function due() {
        Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-due'));

        $page_title = trans('sales/general.page.due.title');
        $page_description = trans('sales/general.page.due.description');

        $agent_list = ['all' => 'All'] + \App\Agent::isActive()->lists('name', 'id')->all();

        $cur_date = Carbon::now()->format('d/m/Y');

        return view('sales.due_notification', compact('agent_list', 'cur_date', 'page_title', 'page_description'));
    }

    public function due_export() {
        Audit::log(Auth::user()->id, request()->ip(), trans('sales/general.audit-log.category'), trans('sales/general.audit-log.msg-due'));

        $agents_builder = \App\Agent::isActive()->with('sales')->with('agent_position');
        if(Input::has('agent_id') && Input::get('agent_id') != 'all') {
            $agents_builder->where('id', '=', Input::get('agent_id'));
        }
        $date1 = Carbon::createFromFormat('d/m/Y', Input::get('date1'));
        $date2 = Carbon::createFromFormat('d/m/Y', Input::get('date2'));
        $agents = $agents_builder->get();
        $sales = [];
        $ctr = 0;
        foreach ($agents as $agent) {
            $sale = $agent->sales()->isActive()->dueBetween($date1, $date2);
            $ctr += $sale->count();
            $sales[$agent->id] = $sale->get();
        }

        if($ctr == 0) {
            Flash::error( trans('sales/general.error.no-data') );
            return redirect()->back();
        }

        $html = \View::make('pdf.sales_due', compact('agents', 'sales', 'date1', 'date2'))->render();
        $html = str_replace('id=', 'class=', $html); // DOMPDF workaround -> https://github.com/barryvdh/laravel-dompdf/issues/96

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('sales_due.pdf');
    }

    public function export(SalesExcelExport $export)
    {
        // TODO: change pdf generator to manual attribute printing, get agent position name
        if(Input::has('type')) {
            $builder = \App\Sale::with('agent')->with('customer')->with('product')->where('is_active', 1);
			
            if(Input::has('NBRO') && Input::get('NBRO') != 'all') {
                // apply join date filter
                $builder->where('NBRO', Input::get('NBRO'));
            }
			
            if(Input::has('MGI_start_date_filter1') && Input::has('MGI_start_date_filter2')) {
                // apply join date filter
                $builder->whereBetween('MGI_start_date', [
                    \DateTime::createFromFormat('d/m/Y', Input::get('MGI_start_date_filter1')),
                    \DateTime::createFromFormat('d/m/Y', Input::get('MGI_start_date_filter2'))
                ]);
				//$builder->whereBetween('MGI_start_date',[date_format(date_create(Input::get('MGI_start_date_filter1')),'d/m/Y'),date_format(date_create(Input::get('MGI_start_date_filter2')),'d/m/Y')]);
            }
            /*if(Input::has('insurance_start_date_filter1') && Input::has('insurance_start_date_filter2')) {
                // apply join date filter
                $builder->whereBetween('insurance_start_date', [
                    \DateTime::createFromFormat('d/m/Y', Input::get('insurance_start_date_filter1')),
                    \DateTime::createFromFormat('d/m/Y', Input::get('insurance_start_date_filter2'))
                ]);
            }*/
            if(Input::has('chkExp')) {
                // apply checkbox column filter
                $builder->select(Input::get('chkExp'));
            }
            $data = $builder->whereIn('branch_office_id',\App\BranchOffice::getBranchOfficesID())->get();
			
            $columns = Input::get('chkExp');
            if($data->count() == 0) {
                Flash::error( trans('sales/general.error.no-data') );
                return redirect()->back();
            }
			\Config::set('global.export_type',Input::get('type'));
			$dataArray = $data->toArray();
			$export->data = $dataArray;
			return $export->handleExport();
            //switch(Input::get('type')) {
            //    case 'pdf':
            //    default:
            //        $html = \View::make('pdf.sales', compact('data', 'columns', 'enabledOnly'))->render();
            //        $html = str_replace('id=', 'class=', $html); // DOMPDF workaround -> https://github.com/barryvdh/laravel-dompdf/issues/96
			//		//dd($html);
			//		set_time_limit(3600);
			//		/*
			//		$pdf = \App::make('dompdf.wrapper');
			//		$pdf->setPaper('A4','landscape');
			//		$pdf->loadHTML($html);
			//		return $pdf->stream('invoice.pdf');*/
			//		$mpdf = new \mPDF("en", "A4-L", "12");
            //        $mpdf->WriteHTML($html);
            //        return $mpdf->Output();
            //    case 'xlsx':
            //        $dataArray = $data->toArray();
            //        $export->data = $dataArray;
            //        return $export->handleExport();
            //}
			
        } else {
            Flash::error( trans('sales/general.error.no-data') );
            return redirect()->back();
        }
    }
}
