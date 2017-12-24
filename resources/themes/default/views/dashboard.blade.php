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
    @include('partials._head_extra_bsdatepicker_css')
@endsection

@section('content')
    <div class='row'>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $count_sales }}</h3>

                    <p>Sales (Period {{ $current_period }})</p>
                </div>
                <div class="icon">
                    <i class="ion ion-cash"></i>
                </div>
                <a href="{!! route('sales.create') !!}" class="small-box-footer"><strong>Create new </strong><i class="fa fa-plus-circle"></i></a>
                <a href="{!! route('sales.index') !!}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $count_customers }}</h3>

                    <p>Active Customers</p>
                </div>
                <div class="icon">
                    <i class="ion ion-happy-outline"></i>
                </div>
                <a href="{!! route('customers.create') !!}" class="small-box-footer"><strong>Create new </strong><i class="fa fa-plus-circle"></i></a>
                <a href="{!! route('customers.index') !!}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
                <div class="inner">
                    <h3>{{ $count_agents }}</h3>

                    <p>Active Agents</p>
                </div>
                <div class="icon">
                    <i class="ion ion-briefcase"></i>
                </div>
                <a href="{!! route('agents.create') !!}" class="small-box-footer"><strong>Create new </strong><i class="fa fa-plus-circle"></i></a>
                <a href="{!! route('agents.index') !!}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ date('d') }}</h3>

                    <p>{{ date('F') }} {{ date('Y') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-calendar"></i>
                </div>
                <a href="#" class="small-box-footer"><strong>{{ date('l') }}</strong></a>
                <a href="#" class="small-box-footer">Today</a>
            </div>
        </div>
        <div class='col-md-8 col-xs-12'>



            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">Reminders</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">

                    @foreach($reminderApproval as $rem)
                        @include('partials._reminder_approval_approved', ['reminder' => $rem])
                    @endforeach
                    @foreach($reminderEval as $rem)
                        @include('partials._reminder_agent_position', ['reminder' => $rem])
                    @endforeach
                    @foreach($reminderRollover as $rem)
                        @include('partials._reminder_rollover', ['reminder' => $rem])
                    @endforeach
                    @if($totalApproval != 0)
                        @include('partials._reminder_approval_count', ['total' => $totalApproval])
                    @endif
                    @if($reminder_count == 0)
                        <div class="callout callout-default">
                            <h4><i class="icon fa fa-check"></i> No action needed!</h4>

                            <p>There is nothing to act here.</p>
                        </div>
                    @endif

                </div><!-- /.box-body -->
                @if(\Auth::getUser()->hasRole('owner'))
                <div class="box-footer">
                    <a href="{!! route('approvals.index') !!}" class="btn btn-default btn-sm pull-right">View all approvals <i class="fa fa-arrow-circle-right"></i></a>
                </div><!-- /.box-footer-->
                @endif
            </div><!-- /.box -->
            <div class="box box-danger">
                <div class="box-header with-border">
                    <i class="fa fa-money"></i>
                    <h3 class="box-title">10 Recent Sales</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <?=
                    // using Nayjest/Grid table generator based on a data provider
                    (new Grid(
                            (new GridConfig)
                                    ->setDataProvider(
                                            $dataProvider
                                    )
                                    ->setName('sales_index')
                                    ->setCachingTime(10)
                                    ->setPageSize(10)
                                    ->setColumns([
                                            (new FieldConfig)
                                                    ->setName('id')
                                                    ->setLabel('ID')
                                                    ->setSortable(false)
                                                    ->setCallback(
                                                            function($val, DataRow $row) {
                                                                return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $val);
                                                            }
                                                    )
                                        ,
                                            (new FieldConfig)
                                                    ->setName('agents.name')
                                                    ->setLabel('Agent Name')
                                                    ->setSortable(false)
                                                    ->addFilter(
                                                            (new FilterConfig)
                                                                    ->setOperator(FilterConfig::OPERATOR_LIKE)
                                                    )
                                                    ->setCallback(
                                                            function($val, DataRow $row) {
                                                                return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $row->getCellValue('name'));
                                                            }
                                                    )
                                        ,
                                            (new FieldConfig)
                                                    ->setName('customer_name')
                                                    ->setLabel('Customer Name')
                                                    ->setSortable(false)
                                                    ->addFilter(
                                                            (new FilterConfig)
                                                                    ->setOperator(FilterConfig::OPERATOR_LIKE)
                                                    )
                                                    ->setCallback(
                                                            function($val, DataRow $row) {
                                                                return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $val);
                                                            }
                                                    )
                                        ,
                                            (new FieldConfig)
                                                    ->setName('MGI_start_date')
                                                    ->setLabel('MGI Start Date')
                                                    ->setSortable(false)
                                                    ->setCallback(
                                                            function($val, DataRow $row) {
                                                                return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $val);
                                                            }
                                                    )
                                        ,
                                            (new FieldConfig)
                                                    ->setName('nominal')
                                                    ->setLabel('Nominal')
                                                    ->setSortable(false)
                                                    ->addFilter(
                                                            (new FilterConfig)
                                                                    ->setOperator(FilterConfig::OPERATOR_LIKE)
                                                    )
                                                    ->setCallback(
                                                            function($val, DataRow $row) {
                                                                return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), \App\Money::format('%(.2n', $val));
                                                            }
                                                    )
                                        ,
                                            (new FieldConfig)
                                                    ->setName('MGI')
                                                    ->setLabel('MGI')
                                                    ->setSortable(false)
                                                    ->setCallback(
                                                            function($val, DataRow $row) {
                                                                return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $val);
                                                            }
                                                    )
                                        ,
                                            (new FieldConfig)
                                                    ->setName('action')
                                                    ->setLabel('Actions')
                                                    ->setSortable(false)
                                                    ->setCallback(
                                                            function($val, DataRow $row) {
                                                                $id = $row->getCellValue('id');
                                                                $showAction = html_entity_decode(
                                                                                \Html::link(route('sales.show', $id), '<span class="fa fa-eye"></span> ' . trans('general.button.view'), [
                                                                                        'class' => 'btn btn-sm btn-default btn-action',
                                                                                ])
                                                                        ) . ' ';
                                                                return $showAction;
                                                            }
                                                    )
                                        ,
                                    ])->setComponents([
                                            (new THead)
                                                    ->setComponents([
                                                            (new ColumnHeadersRow)
                                                    ])
                                        ,

                                    ])
                    )
                    )
                    ?>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <a href="{!! route('sales.index') !!}" class="btn btn-default btn-sm pull-right">View all sales <i class="fa fa-arrow-circle-right"></i></a>
                </div><!-- /.box-footer-->
            </div>

        </div><!-- /.col -->
        <div class='col-md-4 col-xs-12'>
            <div class="box box-success">
                <div class="box-header with-border">
                    <i class="fa fa-calendar"></i>

                    <h3 class="box-title">Calendar</h3>
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                    <!-- /. tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <!--The calendar -->
                    <div id="calendar" style="width: 100%">
                        <div class="datepicker" data-date="{{ date('d/m/Y') }}">
                        </div>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <a href="{!! route('admin.holidays.index') !!}" class="btn btn-default btn-sm pull-right">Set holidays <i class="fa fa-arrow-circle-right"></i></a>
                </div><!-- /.box-footer-->
            </div>
            <div class="box box-warning">
                <div class="box-header">
                    <i class="fa fa-file-text"></i>
                    <h3 class="box-title">Slips</h3>
                </div>
                <div class="box-body">
                    <a class="btn btn-app" href="{!! route('slips.commission.index') !!}">
                        <i class="ion ion-bag"></i> Commission
                    </a>
                    <a class="btn btn-app" href="{!! route('slips.overriding.index') !!}">
                        <i class="ion ion-code-download"></i> Overriding
                    </a>
                    <a class="btn btn-app" href="{!! route('slips.topoverriding.index') !!}">
                        <i class="ion ion-network"></i> Rec Fee
                    </a>
                    <a class="btn btn-app" href="{!! route('slips.tax.index') !!}">
                        <i class="ion ion-flag"></i> Tax
                    </a>
                </div>
                <!-- /.box-body -->
            </div>
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <script src="{{ asset ("/bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({'daysOfWeekDisabled': [0,6], 'todayHighlight': true, 'format': "dd/mm/yyyy", 'datesDisabled': {!! $holidays_date_json !!} });
        });
    </script>
    <style>
        .datepicker table tr td.active, .datepicker table tr td.today {
            border: 0;
        }
        .datepicker table tr td.disabled {
            color: #d9534f;
        }
        .datepicker .datepicker-days table, .datepicker .datepicker-inline, .datepicker-days {
            width: 100%;
        }
        .btn-app {
            width: 22.5%;
        }
    </style>
@endsection