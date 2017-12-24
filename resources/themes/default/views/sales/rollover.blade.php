@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_bsdatepicker_css')
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('sales/general.page.rollover.section-title') !!}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::open( ['route' => 'sales.store', 'id' => 'form_rollover_sale', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('reminder_id', $reminder_id, ['class' => 'form-control']) !!}
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_basic" data-toggle="tab" aria-expanded="true">{!! trans('sales/general.tabs.basic') !!}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_basic">

                                <div class="form-group">
                                    {!! Form::label('SPAJ', trans('sales/general.columns.SPAJ'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('SPAJ', $oldSale->SPAJ, ['class' => 'form-control', 'tabindex' => 1]) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('agent_id', trans('sales/general.columns.agent_id'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('agent_id', App\Agent::getAgents_ForDropDown(), $oldSale->agent_id, ['class' => 'form-control select2', 'style' => "width: 100%", 'tabindex' => 10]) !!}
                                            <span class="input-group-addon">
                                        <span class="fa fa-fw fa-child"></span>
                                    </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('product_id', trans('sales/general.columns.product_id'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('product_id', App\Product::getProducts_ForDropDown(), $oldSale->product_id, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 10]) !!}
                                            <span class="input-group-addon">
                                        <span class="fa fa-fw fa-cubes"></span>
                                    </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('number', trans('sales/general.columns.number'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('number', $oldSale->number, ['class' => 'form-control', 'tabindex' => 2]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('customer_id', trans('sales/general.columns.customer_id'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('customer_id', App\Customer::getCustomers_ForDropDown(), $oldSale->customer_id, ['class' => 'form-control select2', 'style' => "width: 100%", 'tabindex' => 10]) !!}
                                            <span class="input-group-addon">
                                        <span class="fa fa-fw fa-users"></span>
                                    </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('MGI', trans('sales/general.columns.MGI'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('MGI', $MGIs, $oldSale->MGI, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 10]) !!}
                                            <span class="input-group-addon">
                                        <span class="fa fa-fw fa-dollar"></span>
                                    </span>
                                        </div>
                                    </div>
                                </div>

                                {!! Form::hidden('currency', 'IDR', ['class' => 'form-control']) !!}

                                <div class="form-group">
                                    {!! Form::label('MGI_start_date', trans('sales/general.columns.MGI_start_date'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            {!! Form::text('MGI_start_date', Carbon\Carbon::create(Carbon\Carbon::now()->year, Carbon\Carbon::createFromFormat('d/m/Y', $oldSale->MGI_start_date)->month, Carbon\Carbon::createFromFormat('d/m/Y', $oldSale->MGI_start_date)->day)->format('d/m/Y'), ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'tabindex' => 4]) !!}
                                            <span class = "input-group-addon"><span class="fa fa-fw fa-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                {!! Form::label('nominal', trans('sales/general.columns.nominal'), ['class' => 'control-label col-sm-2'] ) !!}
                                <div class="col-sm-10">
                                    {!! Form::text('m_nominal', $oldSale->nominal * 100, ['class' => 'form-control maskmoney', 'tabindex' => 13, 'id' => 'm_nominal']) !!}
                                    {!! Form::hidden('nominal', $oldSale->nominal, ['id' => 'nominal']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('interest', trans('sales/general.columns.interest'), ['class' => 'control-label col-sm-2'] ) !!}
                                <div class="col-sm-10">
                                    {!! Form::text('interest', $oldSale->interest, ['class' => 'form-control', 'tabindex' => 13]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('additional', trans('sales/general.columns.additional'), ['class' => 'control-label col-sm-2'] ) !!}
                                <div class="col-sm-10">
                                    {!! Form::text('m_additional', $oldSale->additional * 100, ['class' => 'form-control maskmoney', 'tabindex' => 13, 'id' => 'm_additional']) !!}
                                    {!! Form::hidden('additional', $oldSale->additional, ['id' => 'additional']) !!}
                                </div>
                            </div>

                        </div><!-- /.tab-content -->
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 3] ) !!}
                            <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-fw fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                            <div class="pull-right">
                                <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('sales.index') !!}"><span class="fa fa-fw fa-times"></span> {{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

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
            $('#m_nominal').maskMoney('mask');
            $('#m_additional').maskMoney({'precision': 2, thousands:'.', 'decimal':','}).on('change', function() {
                $('#additional').val($('#m_additional').maskMoney('unmasked')[0]);
            });
            $('#m_additional').maskMoney('mask');
        });
    </script>
    <script type="text/javascript" src="{!! asset('vendor/jsvalidation/js/jsvalidation.js') !!}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\CreateSaleRequest', '#form_rollover_sale') !!}
@endsection
