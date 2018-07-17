@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_bsdatepicker_css')
    @include('partials._head_extra_select2_css')
    @include('partials._head_extra_cities_add')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('boardofdirector/general.page.edit.section-title') !!}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::model( $boardOfDirector, ['route' => ['admin.boardofdirector.update', $boardOfDirector->id], 'method' => 'PATCH', 'id' => 'form_edit_board_of_director', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}

                              <div class="form-group">
                        {!! Form::label('bod_name', trans('boardofdirector/general.columns.bod_name'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('bod_name', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('address', trans('boardofdirector/general.columns.address'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('address', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('state', trans('boardofdirector/general.columns.state'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('state', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('city', trans('boardofdirector/general.columns.city'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('city', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('zipcode', trans('boardofdirector/general.columns.zipcode'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('zipcode', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone_number', trans('boardofdirector/general.columns.phone_number'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('phone_number', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', trans('boardofdirector/general.columns.email'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('email', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('type', trans('boardofdirector/general.columns.type'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('type', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('identity_number', trans('boardofdirector/general.columns.identity_number'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('identity_number', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('NPWP', trans('boardofdirector/general.columns.npwp'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('NPWP', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('position', trans('boardofdirector/general.columns.position'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('position', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('bank', trans('boardofdirector/general.columns.bank_name'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('bank', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('bank_branch', trans('boardofdirector/general.columns.bank_branch'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('bank_branch', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('account_number', trans('boardofdirector/general.columns.acc_number'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('account_number', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('account_name', trans('boardofdirector/general.columns.acc_name'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('account_name', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                        </div>
                    </div>


                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 3] ) !!}
                                <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                                <div class="pull-right">
                                    <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('admin.boardofdirector.index') !!}"><span class="fa fa-times"></span> {{ trans('general.button.cancel') }}</a>
                                </div>
                            </div>
                        </div>
                    <!-- /.box-body -->
                    {!! Form::close() !!}


                </div><!-- /.box-body -->
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
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
    {!! JsValidator::formRequest('App\Http\Requests\EditProductRequest', '#form_edit_product') !!}
@endsection
