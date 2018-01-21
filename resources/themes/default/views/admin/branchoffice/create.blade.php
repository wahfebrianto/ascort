@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_bsdatepicker_css')
    @include('partials._head_extra_select2_css')
    @include('partials._head_extra_cities_add')
@endsection

@section('content')

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('branch_office/general.page.create.title') !!}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">
                    {!! Form::open( ['route' => 'admin.branchoffice.store', 'id' => 'form_create_branch_office', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}

                    <div class="form-group">
                        {!! Form::label('branch_name', trans('branch_office/general.columns.branch_name'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('branch_name', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('address', trans('branch_office/general.columns.address'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('address', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('state', trans('customers/general.columns.state'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('state', array_keys(config('cities')), null, ['class' => 'form-control', 'id' => 'state',  'style' => "width: 100%", 'tabindex' => 10]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-globe"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('city', trans('customers/general.columns.city'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('city', [], null, ['class' => 'form-control',  'style' => "width: 100%", 'tabindex' => 11]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-map-marker"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('phone_number', trans('branch_office/general.columns.phone_number'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('phone_number', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 3] ) !!}
                            <a id="resetFormAnchor" disabled onclick="resetForm($('#form_create_branch_office'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                            <div class="pull-right">
                                <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('admin.branchoffice.index') !!}"><span class="fa fa-times"></span> {{ trans('general.button.cancel') }}</a>
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

    <!-- Select2 4.0.0 -->
    <script src="{{ asset ("/bower_components/admin-lte/select2/js/select2.min.js") }}" type="text/javascript"></script>

    <script src="{{ asset ("/bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js") }}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}" type="text/javascript"></script>
    <script>
    $(document).ready(function() {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
        $('.datepicker').datepicker().attr('placeholder', 'dd/mm/yyyy');
        $('.select2').select2();
    });
    </script>

    <script type="text/javascript" src="{!! asset('vendor/jsvalidation/js/jsvalidation.js') !!}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\CreateBranchOfficeRequest', '#form_create_branch_office') !!}

    @endsection
