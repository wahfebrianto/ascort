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
                    <h3 class="box-title">{!! trans('admin/mgi/general.page.edit.section-title') !!}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::model( $mgi, ['route' => ['admin.mgi.update', $code], 'method' => 'PATCH', 'id' => 'form_edit_mgi', 'class' => 'form-horizontal'] ) !!}

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_basic" data-toggle="tab" aria-expanded="true">{!! trans('admin/mgi/general.tabs.basic') !!}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_basic">
                
                                <div class="form-group">
                                    {!! Form::label('code', trans('admin/mgi/general.columns.code'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('code', $code, ['class' => 'form-control', 'tabindex' => 1, 'readonly' => true]) !!}
                                    </div>
                                </div>
                
                                <div class="form-group">
                                    {!! Form::label('name', trans('admin/mgi/general.columns.name'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('name', $mgi[0], ['class' => 'form-control', 'tabindex' => 2]) !!}
                                    </div>
                                </div>
                
                                <div class="form-group">
                                    {!! Form::label('month', trans('admin/mgi/general.columns.month'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('month', $mgi[1], ['class' => 'form-control', 'tabindex' => 3]) !!}
                                    </div>
                                </div>

                            </div><!-- /.tab-content -->
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
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')

    <script type="text/javascript" src="{!! asset('vendor/jsvalidation/js/jsvalidation.js') !!}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\MGIRequest', '#form_edit_mgi') !!}
@endsection
