@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_bsdatepicker_css')
@endsection

@section('content')

    <div class='row'>
        <div class='col-md-12 col-lg-12'>
            <!-- Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('admin/holidays/general.page.create.title') !!}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">
                    {!! Form::open( ['route' => 'admin.holidays.store', 'id' => 'form_create_holidays', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}

                    <div class="form-group">
                        {!! Form::label('date', trans('admin/holidays/general.columns.date'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::text('date', null, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'style' => "width: 100%", 'tabindex' => 1]) !!}
                                <span class="input-group-addon">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('description', trans('admin/holidays/general.columns.description'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::text('description', null, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 2]) !!}
                                <span class="input-group-addon">
                                                <span class="fa fa-list"></span>
                                            </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('type', trans('admin/holidays/general.columns.type'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('type', [
                                    'Normal' => 'Normal Holiday',
                                    'Cuti' => 'Idul Fitri / Christmas'
                                ], null, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 3]) !!}
                                <span class="input-group-addon">
                                                <span class="fa fa-list"></span>
                                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 23] ) !!}
                            <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-fw fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                            <div class="pull-right">
                                <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('admin.holidays.index') !!}"><span class="fa fa-fw fa-times"></span> {{ trans('general.button.cancel') }}</a>
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
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker();
        });
    </script>
    <script type="text/javascript" src="{!! asset('vendor/jsvalidation/js/jsvalidation.js') !!}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\HolidayRequest', '#form_create_holidays') !!}

    @endsection


