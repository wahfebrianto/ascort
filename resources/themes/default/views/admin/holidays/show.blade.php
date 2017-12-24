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
                    <h3 class="box-title">{{ trans('admin/holidays/general.page.show.section-title') }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::model( $holiday, ['route' => ['admin.holidays.index'], 'method' => 'GET', 'id' => 'form_edit_holidays', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_basic" data-toggle="tab" aria-expanded="true">{!! trans('admin/holidays/general.tabs.basic') !!}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_basic">

                                <div class="form-group">
                                    {!! Form::label('date', trans('admin/holidays/general.columns.date'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::text('date', $holiday->formattedDate, ['class' => 'form-control datepicker', 'style' => "width: 100%", 'tabindex' => 1, 'disabled']) !!}
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
                                            {!! Form::text('description', null, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 2, 'disabled']) !!}
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
                                            ], null, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 3, 'disabled']) !!}
                                            <span class="input-group-addon">
                                                            <span class="fa fa-list"></span>
                                                        </span>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- /.tab-pane -->

                        </div><!-- /.tab-content -->
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit(trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('admin.holidays.edit', $holiday->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'><span class="fa fa-edit"></span> {{ trans('general.button.edit') }}</a>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div><!-- /.box-body -->
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
    @endsection

@section('body_bottom')


@endsection
