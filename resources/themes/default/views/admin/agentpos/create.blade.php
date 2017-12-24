@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_bsdatepicker_css')
    @include('partials._head_extra_select2_css')
@endsection

@section('content')

    <div class='row'>
        <div class='col-md-12 col-lg-12'>
            <!-- Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('admin/agentpos/general.page.create.title') !!}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">
                    {!! Form::open( ['route' => 'admin.agentpos.store', 'id' => 'form_create_agentpos', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}

                    <div class="form-group">
                        {!! Form::label('parent_id', trans('admin/agentpos/general.columns.parent_id'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('parent_id', App\AgentPosition::getAgentPositions_ForParentDropDown(), null, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 1]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-briefcase"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name', trans('admin/agentpos/general.columns.name'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('name', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('acronym', trans('admin/agentpos/general.columns.acronym'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('acronym', null, ['class' => 'form-control', 'tabindex' => 3]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('level', trans('admin/agentpos/general.columns.level'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::number('level', null, ['class' => 'form-control', 'tabindex' => 4]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 5] ) !!}
                            <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                            <div class="pull-right">
                                <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('admin.agentpos.index') !!}"><span class="fa fa-times"></span> {{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class='col-md-12 col-lg-6'>
            <!-- Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('admin/agentpos/general.page.create.general-info') }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

    @endsection
    @section('body_bottom')


    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
        });
    </script>
    <script type="text/javascript" src="{!! asset('vendor/jsvalidation/js/jsvalidation.js') !!}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\CreateAgentPositionRequest', '#form_create_agentpos') !!}

    @endsection


