@extends('layouts.master')

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('branch_office/general.page.show.section-title') }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::model( $branchOffice, ['route' => ['admin.branchoffice.index'], 'method' => 'GET', 'id' => 'form_edit_branchoffice', 'class' => 'form-horizontal'] ) !!}
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_basic" data-toggle="tab" aria-expanded="true">{!! trans('branch_office/general.tabs.basic') !!}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_basic">
                                <div class="form-group">
                                    {!! Form::label('branch_name', trans('branch_office/general.columns.branch_name'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('branch_name', null, ['class' => 'form-control', 'tabindex' => 1, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('address', trans('branch_office/general.columns.address'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('address', null, ['class' => 'form-control', 'tabindex' => 2, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('city', trans('branch_office/general.columns.city'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('city', null, ['class' => 'form-control', 'tabindex' => 3, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('state', trans('branch_office/general.columns.state'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('state', null, ['class' => 'form-control', 'tabindex' => 4, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('phone_number', trans('branch_office/general.columns.phone_number'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('phone_number', null, ['class' => 'form-control', 'tabindex' => 5, 'disabled']) !!}
                                    </div>
                                </div>

                            </div><!-- /.tab-pane -->

                        </div><!-- /.tab-content -->
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit(trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('admin.branchoffice.edit', $branchOffice->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'><span class="fa fa-edit"></span> {{ trans('general.button.edit') }}</a>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div><!-- /.box-body -->
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection
