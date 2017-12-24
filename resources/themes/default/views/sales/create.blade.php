@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_bsdatepicker_css')
    @include('partials._head_extra_select2_css')
@endsection

@section('content')

    <div class='row'>
        <div class='col-md-12 col-lg-12'>
            <!-- Box -->
            <div class="box box-primary fa-bg fa-bg-money">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('sales/general.page.create.title') !!}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">
                    {!! Form::open( ['route' => 'sales.index', 'id' => 'form_create_sale', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}
					
					<div class="form-group">
                        {!! Form::label('branch_office_id', trans('sales/general.columns.branch_office_id'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('branch_office_id', App\BranchOffice::getBranchOffices_ForDropDown(), null, ['class' => 'form-control select2', 'style' => "width: 100%", 'tabindex' => 5]) !!}
                                <span class="input-group-addon">
                            <span class="fa fa-fw fa-fw fa-building-o"></span>
                        </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('agent_id', trans('sales/general.columns.agent_id'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('agent_id', [null => "Select Agent"] + App\Agent::getAgents_ForDropDown(), '-', ['class' => 'form-control select2', 'style' => "width: 100%", 'tabindex' => 2]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-fw fa-child"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('product_id', trans('sales/general.columns.product_id'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('product_id', App\Product::getProducts_ForDropDown(), null, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 3]) !!}
                                <span class="input-group-addon">
                            <span class="fa fa-fw fa-fw fa-cubes"></span>
                        </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('number', trans('sales/general.columns.number'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('number', null, ['class' => 'form-control', 'tabindex' => 4]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('customer_id', trans('sales/general.columns.customer_id'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('customer_id', [null => "Select Customer"] + App\Customer::getCustomers_ForDropDown(), null, ['class' => 'form-control select2', 'style' => "width: 100%", 'tabindex' => 5]) !!}
                                <span class="input-group-addon">
                            <span class="fa fa-fw fa-fw fa-users"></span>
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('MGI', trans('sales/general.columns.MGI'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('MGI', $MGIs, null, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 6]) !!}
                                <span class="input-group-addon">
                            <span class="fa fa-fw fa-fw fa-dollar"></span>
                        </span>
                            </div>
                        </div>
                    </div>

                    {!! Form::hidden('currency', 'IDR', ['class' => 'form-control']) !!}

                    <div class="form-group">
                        {!! Form::label('MGI_start_date', trans('sales/general.columns.MGI_start_date'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group">
                                {!! Form::text('MGI_start_date', null, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'tabindex' => 7]) !!}
                                <span class = "input-group-addon"><span class="fa fa-fw fa-fw fa-calendar"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('nominal', trans('sales/general.columns.nominal'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('m_nominal', null, ['class' => 'form-control maskmoney', 'tabindex' => 8, 'id' => 'm_nominal']) !!}
                            {!! Form::hidden('nominal', null, ['id' => 'nominal']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('interest', trans('sales/general.columns.interest'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group">
                                {!! Form::text('interest', 12, ['class' => 'form-control', 'tabindex' => 9]) !!}
                                <span class="input-group-addon">
                                    %
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('additional', trans('sales/general.columns.additional'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('m_additional', null, ['class' => 'form-control maskmoney', 'tabindex' => 8, 'id' => 'm_additional']) !!}
                            {!! Form::hidden('additional', null, ['id' => 'additional']) !!}
                        </div>
                    </div>
					
					<div class="form-group">
                        {!! Form::label('bank', trans('sales/general.columns.bank'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('bank', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
					<div class="form-group">
                        {!! Form::label('bank_branch', trans('sales/general.columns.bank_branch'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('bank_branch', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
					<div class="form-group">
                        {!! Form::label('account_name', trans('sales/general.columns.account_name'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('account_name', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
					<div class="form-group">
                        {!! Form::label('account_number', trans('sales/general.columns.account_number'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('account_number', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
					
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 15] ) !!}
                            <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-fw fa-fw fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                            <div class="pull-right">
                                <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('sales.index') !!}"><span class="fa fa-fw fa-fw fa-times"></span> {{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->

    @endsection


            <!-- Optional bottom section for modals etc... -->
    @section('body_bottom')
    <script src="{{ asset ("/bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/jquery.maskmoney/dist/jquery.maskMoney.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/select2/js/select2.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
            $('.datepicker').datepicker().attr('placeholder', 'dd/mm/yyyy');
            $('.select2').select2();
            $('#m_nominal').maskMoney({'precision': 2, thousands:'.', 'decimal':','}).on('change', function() {
                $('#nominal').val($('#m_nominal').maskMoney('unmasked')[0]);
            });
            $('#m_additional').maskMoney({'precision': 2, thousands:'.', 'decimal':','}).on('change', function() {
                $('#additional').val($('#m_additional').maskMoney('unmasked')[0]);
            });
        });
    </script>
    <script type="text/javascript" src="{!! asset('vendor/jsvalidation/js/jsvalidation.js') !!}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\CreateSaleRequest', '#form_create_sale') !!}

    @endsection


