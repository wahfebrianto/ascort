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
    @include('partials._head_extra_table_action_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>

            <!-- Box -->
                <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('agents/general.page.history.table-title') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                <?=
                    // using Nayjest/Grid table generator based on a data provider
                    // TODO: Change Agent Position filter to dropdown
                    (new Grid(
                        (new GridConfig)
                            ->setDataProvider(
                                $dataProvider
                            )
                            ->setName('agents_history')
                            // ->setCachingTime(10)
                            ->setPageSize(100)
                            ->setColumns([
                                (new FieldConfig)
                                        ->setName('agents.name')
                                        ->setLabel('Name')
                                        ->setSortable(true)
                                        ->addFilter(
                                                (new FilterConfig)
                                                        ->setOperator(FilterConfig::OPERATOR_LIKE)
                                        )
                                        ->setCallback(
                                                function($val, DataRow $row) {
                                                    return \Html::link(route('agents.show', ['id' => $row->getCellValue('id')]), $row->getCellValue('name'));
                                                }
                                        )
                                ,
                                (new FieldConfig)
                                        ->setName('agent_positions.name')
                                        ->setLabel('Name')
                                        ->setSortable(true)
                                        ->addFilter(
                                                (new FilterConfig)
                                                        ->setOperator(FilterConfig::OPERATOR_LIKE)
                                        )
                                        ->setCallback(
                                                function($val, DataRow $row) {
                                                    return \Html::link(route('agents.show', ['id' => $row->getCellValue('id')]), $row->getCellValue('apname'));
                                                }
                                        )
                                ,
                            ])->setComponents([
                                (new THead)
                                    ->setComponents([
                                        (new OneCellRow)
                                            ->setRenderSection(RenderableRegistry::SECTION_BEGIN)
                                            ->setComponents([
                                                (new HtmlTag)
                                                    ->setContent('Table Tools <span class="fa fa-chevron-right"></span>')
                                                    ->setTagName('span')
                                                    ->setAttributes([
                                                        'class' => 'typo-all-caps',
                                                    ]),
                                                (new HtmlTag)
                                                    ->setContent('&nbsp;&nbsp;')
                                                    ->setTagName('span'),
                                                (new RecordsPerPage)
                                                    ->setVariants([10, 20, 50, 100, 200])
                                                    ->setName('record_count'),
                                                (new ColumnsHider),
                                                (new HtmlTag)
                                                    ->setContent('<span class="glyphicon glyphicon-refresh"></span> Reset Page and Filter')
                                                    ->setTagName('a')
                                                    ->setAttributes([
                                                        'class' => 'btn btn-danger btn-sm pull-right',
                                                        'href' => route('agents.index'),
                                                        'onclick' => 'document.cookie = "agents_index-columns_hider-cookie" + "=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/;"',
                                                        'style' => 'margin-left: 3px;'
                                                    ]),
                                                (new HtmlTag)
                                                    ->setContent('<span class="fa fa-search"></span> Refresh and Apply Filter')
                                                    ->setTagName('button')
                                                    ->setAttributes([
                                                        'class' => 'btn bg-navy btn-sm pull-right',
                                                        'style' => 'margin-left: 3px;'
                                                    ]),
                                            ])
                                            ->setName('globalActionCell'),
                                        (new ColumnHeadersRow),
                                        (new FiltersRow)
                                    ])
                                ,
                                (new TFoot)
                                    ->setComponents([
                                        (new OneCellRow)
                                            ->setComponents([
                                                (new Pager),
                                                (new HtmlTag)
                                                    ->setAttributes(['class' => 'pull-right'])
                                                    ->addComponent(new ShowingRecords),
                                            ])
                                    ])
                                ,
                            ])
                        )
                    )
                ?>
                </div>
            </div>
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

            <!-- Optional bottom section for modals etc... -->
@section('body_bottom')

    <script src="{{ asset ("/bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js") }}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.modal input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
            $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $('.datepicker').datepicker().attr('placeholder', 'dd/mm/yyyy');
        });
    </script>

    @include('partials._body_bottom_grid_general_checkbox', ['module_name' => 'agents'])
@endsection
