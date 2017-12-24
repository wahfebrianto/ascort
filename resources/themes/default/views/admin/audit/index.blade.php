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
                    <h3 class="box-title">{{ trans('admin/audit/general.page.index.table-title') }}</h3>
                    &nbsp;|&nbsp;


                    <a class="btn btn-danger btn-sm" href="{!! route('admin.audit.purge') !!}" title="{{ trans('admin/audit/general.button.purge', ['purge_retention' => $purge_retention]) }}"
                    onclick="return confirm('Are you sure you want to delete all log?')">
                        <i class="fa fa-eraser"></i> Delete Old Log
                    </a>
                </div>
                <div class="box-body">

                    <?=
                    // using Nayjest/Grid table generator based on a data provider
                    (new Grid(
                        (new GridConfig)
                            ->setDataProvider(
                                $dataProvider
                            )
                            ->setName('audit_index')
                            // ->setCachingTime(10)
                            ->setPageSize(10)
                            ->setColumns([
                                (new FieldConfig)
                                    ->setName('users.username')
                                    ->setLabel('Username')
                                    ->setSortable(true)->addFilter(
                                        (new FilterConfig)
                                                ->setOperator(FilterConfig::OPERATOR_LIKE)
                                    )
                                    ->setCallback(
                                        function($val, DataRow $row) {
                                            return \Html::link(route('admin.audit.show', ['id' => $row->getCellValue('id')]), $row->getCellValue('username'));
                                        }
                                    )
                                ,
                                (new FieldConfig)
                                    ->setName('ip_address')
                                    ->setLabel('Alamat IP')
                                    ->setSortable(true)
                                    ->addFilter(
                                            (new FilterConfig)
                                                    ->setOperator(FilterConfig::OPERATOR_LIKE)
                                    )
                                    ->setCallback(
                                        function($val, DataRow $row) {
                                            return \Html::link(route('admin.audit.show', ['id' => $row->getCellValue('id')]), $val);
                                        }
                                    )
                                ,
                                (new FieldConfig)
                                    ->setName('category')
                                    ->setLabel('Category')
                                    ->setSortable(true)
                                    ->addFilter(
                                            (new FilterConfig)
                                                    ->setOperator(FilterConfig::OPERATOR_LIKE)
                                    )
                                    ->setCallback(
                                        function($val, DataRow $row) {
                                            return \Html::link(route('admin.audit.show', ['id' => $row->getCellValue('id')]), $val);
                                        }
                                    )
                                ,
                                (new FieldConfig)
                                    ->setName('message')
                                    ->setLabel('Message')
                                    ->setSortable(true)
                                    ->addFilter(
                                            (new FilterConfig)
                                                    ->setOperator(FilterConfig::OPERATOR_LIKE)
                                    )
                                    ->setCallback(
                                        function($val, DataRow $row) {
                                            return \Html::link(route('admin.audit.show', ['id' => $row->getCellValue('id')]), $val);
                                        }
                                    )
                                ,
                                (new FieldConfig)
                                    ->setName('created_at')
                                    ->setLabel('Date')
                                    ->setSortable(true)
                                    ->setSorting(Grid::SORT_DESC)
                                    ->setCallback(
                                        function($val, DataRow $row) {
                                            return \Html::link(route('admin.audit.show', ['id' => $row->getCellValue('id')]), $val);
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
                                                \Html::link(route('admin.audit.show', $id), '<span class="fa fa-eye"></span> ' . trans('general.button.view'), [
                                                    'class' => 'btn btn-sm btn-default btn-action',
                                                ])
                                            );
                                            return $showAction;
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
                                                                                    'href' => route('admin.audit.index'),
                                                                                    'onclick' => 'document.cookie = "audit_index-columns_hider-cookie" + "=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/;"',
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

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


            <!-- Optional bottom section for modals etc... -->
@section('body_bottom')
    <!-- Add a spinner to the action replay icon while mouse is hovering. -->
    <script language="JavaScript">
        $('.spin-on-hover').hover(
                function () {
                    $(this).addClass('fa-spin');
                },
                function () {
                    $(this).removeClass('fa-spin');
                }
        );
    </script>
@endsection
