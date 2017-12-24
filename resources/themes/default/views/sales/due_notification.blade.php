<?php
use Nayjest\Grids\Components\Base\RenderableRegistry;
use Nayjest\Grids\Components\ColumnHeadersRow;
use Nayjest\Grids\Components\ColumnsHider;
use Nayjest\Grids\Components\CsvExport;
use Nayjest\Grids\Components\ExcelExport;
use Nayjest\Grids\Components\Filters\DateRangePicker;
use Nayjest\Grids\Components\FiltersRow;
use Nayjest\Grids\Components\HtmlTag;
use Nayjest\Grids\Components\Laravel5\Pager;
use Nayjest\Grids\Components\OneCellRow;
use Nayjest\Grids\Components\RecordsPerPage;
use Nayjest\Grids\Components\RenderFunc;
use Nayjest\Grids\Components\ShowingRecords;
use Nayjest\Grids\Components\TFoot;
use Nayjest\Grids\Components\THead;
use Nayjest\Grids\Components\TotalsRow;
use Nayjest\Grids\DbalDataProvider;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\FilterConfig;
use Nayjest\Grids\Grid;
use Nayjest\Grids\GridConfig;
use Nayjest\Grids\DataRow;
?>

@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_select2_css')
    @include('partials._head_extra_bsdatepicker_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-3'>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('sales/general.page.due.info-title') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    Print notification of nearly due sales here.
                </div>
            </div>
        </div>
        <div class='col-md-9'>
            <div class="box box-primary fa-bg fa-bg-export fa-bg-small">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('sales/general.page.due.table-title') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    <p>{{ trans('sales/general.page.due.export-description') }}</p>
                    <br />
                    <label class="control-label">Filter</label>
                    <div class="border-decor"></div>
                    {!! Form::open( ['route' => 'sales.due_export', 'id' => 'form_export_sales', 'method' => 'GET', 'class' => 'form-horizontal'] ) !!}
                    <div class="form-group">
                        {!! Form::label('agent_id', trans('sales/general.columns.agent_id'), ['class' => 'control-label col-sm-4'] ) !!}
                        <div class="col-sm-8">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('agent_id', $agent_list, 'all', ['class' => 'form-control select2', 'style' => "width: 100%", 'tabindex' => 1]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-fw fa-child"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('date1', 'Start Date', ['class' => 'control-label col-sm-4'] ) !!}
                        <div class="col-sm-8">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::text('date1', $cur_date, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'style' => "width: 100%", 'tabindex' => 2]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-fw fa-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('date2', 'End Date', ['class' => 'control-label col-sm-4'] ) !!}
                        <div class="col-sm-8">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::text('date2', $cur_date, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'style' => "width: 100%", 'tabindex' => 3]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-fw fa-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            {!! Form::submit( trans('general.button.view'), ['class' => 'btn btn-primary btn-submit btn-action', 'id' => 'btn-submit-save', 'tabindex' => 4] ) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div><!-- /.row -->
@endsection


@section('body_bottom')

    <script src="{{ asset ("/bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/select2/js/select2.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker().attr('placeholder', 'dd/mm/yyyy');
            $('.select2').select2();
        });
    </script>
@endsection
