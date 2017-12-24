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
                    <h3 class="box-title">{!! trans('admin/mgi/general.page.create.title') !!}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">
                    {!! Form::open( ['route' => 'admin.mgi.index', 'id' => 'form_create_mgi', 'class' => 'form-horizontal'] ) !!}


                    <div class="form-group">
                        {!! Form::label('code', trans('admin/mgi/general.columns.code'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('code', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name', trans('admin/mgi/general.columns.name'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('name', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('month', trans('admin/mgi/general.columns.month'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('month', null, ['class' => 'form-control', 'tabindex' => 3]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 3] ) !!}
                            <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                            <div class="pull-right">
                                <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('admin.mgi.index') !!}"><span class="fa fa-times"></span> {{ trans('general.button.cancel') }}</a>
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

    <script type="text/javascript" src="{!! asset('vendor/jsvalidation/js/jsvalidation.js') !!}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\MGIRequest', '#form_create_mgi') !!}

    @endsection


