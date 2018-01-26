@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('admin/salecommission/general.page.edit.section-title') !!}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::model( $commission, ['route' => ['admin.salecommission.update', $commission->id], 'method' => 'PATCH', 'id' => 'form_edit_salecommission', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_basic" data-toggle="tab" aria-expanded="true">{!! trans('admin/salecommission/general.tabs.basic') !!}</a></li>
                        </ul>
                    <div class="tab-content">

                        <div class="tab-pane active" id="tab_basic">

                            <div class="form-group">
                                {!! Form::label('agent_position_id', trans('admin/salecommission/general.columns.agent_position_id'), ['class' => 'control-label col-sm-2'] ) !!}
                                <div class="col-sm-10">
                                    <div class="input-group select2-bootstrap-append">
                                        {!! Form::select('agent_position_id', App\AgentPosition::getAgentPositions_ForDropDownCommission($commission->id), null, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 1,'readonly'=>'true']) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-briefcase"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('percentage', trans('admin/salecommission/general.columns.percentage'), ['class' => 'control-label col-sm-2'] ) !!}
                                <div class="col-sm-10">
                                    {!! Form::text('percentage', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                                </div>
                            </div>

                        </div><!-- /.tab-content -->
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 3] ) !!}
                            <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                            <div class="pull-right">
                                <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('admin.salecommission.index') !!}"><span class="fa fa-times"></span> {{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div><!-- /.box-body -->
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')

    <script src="{{ asset ("/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
            $('.datepicker').datepicker();
        });
    </script>
    <script type="text/javascript" src="{!! asset('vendor/jsvalidation/js/jsvalidation.js') !!}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\EditSaleCommissionRequest', '#form_edit_salecommission') !!}
@endsection
