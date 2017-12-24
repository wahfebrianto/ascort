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
                    <h3 class="box-title">{{ trans('admin/holidays/general.page.index.table-title') }}</h3>
                    &nbsp;|&nbsp;
                    <a class="btn btn-primary btn-sm btn-table-head" href="{!! route('admin.holidays.create') !!}" title="Create new holiday">
                        <i class="fa fa-plus-square"></i> Create new holiday
                    </a>
                    <a class="btn btn-info btn-sm btn-table-head" href="{!! route('admin.holidays.load') !!}" title="Create new holiday">
                        <i class="fa fa-cloud"></i> Load from Google API
                    </a>
                    <a class="btn btn-default btn-sm btn-table-head" href="#" id="btnDisableSelected"
                       title="{{ trans('general.button.delete-selected') }}">
                        <i class="fa fa-trash"></i> {{ trans('general.button.delete-selected') }}
                    </a>

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                <?=
                    // using Nayjest/Grid table generator based on a data provider
                    (new Grid(
                        (new GridConfig)
                            ->setDataProvider(
                                $dataProvider
                            )
                            ->setName('holidays_index')
                            // ->setCachingTime(10)
                            ->setPageSize(10)
                            ->setColumns([
                                (new FieldConfig)
                                    ->setName('checkbox')
                                    ->setLabel('<a onclick="toggleCheckbox(); return false;"><span class="fa fa-check-square"></span></a>')
                                    ->setSortable(false)
                                    ->setCallback(
                                        function($val, DataRow $row) {
                                            return \Form::checkbox('chkUser[]', $row->getCellValue('id'));
                                        }
                                    )
                                ,
                                (new FieldConfig)
                                    ->setName('id')
                                    ->setLabel('ID')
                                    ->setSortable(true)
                                    ->setCallback(
                                            function($val, DataRow $row) {
                                                return \Html::link(route('admin.holidays.show', ['id' => $row->getCellValue('id')]), $val);
                                            }
                                    )
                                ,
                                (new FieldConfig)
                                        ->setName('year')
                                        ->setLabel('Year')
                                        ->setSortable(true)
                                        ->setCallback(
                                                function($val, DataRow $row) {
                                                    return \Html::link(route('admin.holidays.show', ['id' => $row->getCellValue('id')]), $val);
                                                }
                                        )
                                ,
                                (new FieldConfig)
                                    ->setName('date')
                                    ->setLabel('Date')
                                    ->setSortable(true)
									->setSorting(Grid::SORT_DESC)
                                    ->setCallback(
                                            function($val, DataRow $row) {
                                                return \Html::link(route('admin.holidays.show', ['id' => $row->getCellValue('id')]), $val);
                                            }
                                    )
                                ,
                                (new FieldConfig)
                                    ->setName('type')
                                    ->setLabel('Type')
                                    ->setSortable(false)
                                    ->setCallback(
                                            function($val, DataRow $row) {
                                                return \Html::link(route('admin.holidays.show', ['id' => $row->getCellValue('id')]), $val);
                                            }
                                    )
                                ,
                                (new FieldConfig)
                                    ->setName('description')
                                    ->setLabel('Description')
                                    ->setSortable(false)
                                    ->setCallback(
                                            function($val, DataRow $row) {
                                                return \Html::link(route('admin.holidays.show', ['id' => $row->getCellValue('id')]), $val);
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
                                                        \Html::link(route('admin.holidays.show', $id), '<span class="fa fa-eye"></span> ' . trans('general.button.view'), [
                                                                'class' => 'btn btn-sm btn-default btn-action',
                                                        ])
                                                ) . ' ';
                                            $editAction = html_entity_decode(
                                                    \Html::link(route('admin.holidays.edit', $id), '<span class="fa fa-edit"></span> ' . trans('general.button.edit'), [
                                                        'class' => 'btn btn-sm bg-purple btn-action',
                                                    ])
                                                ) . ' ';
                                            $enableAction = html_entity_decode(
                                                \Html::link(route('admin.holidays.disable', $id), '<span class="fa fa-trash"></span> ' . trans('general.button.delete'), [
                                                        'class' => 'btn btn-sm btn-danger btn-action',
                                                        'onclick' => 'return confirm(\''. trans('general.confirm.delete', ['ID' => $id]) .'\')',
                                                ])
                                            ) . ' ';
                                            return $showAction . $editAction . $enableAction
                                            ;
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
                                                        'href' => route('admin.holidays.index'),
                                                        'onclick' => 'document.cookie = "products_index-columns_hider-cookie" + "=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/;"',
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
                                        (new ColumnHeadersRow)
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
    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkUser[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
        $(document).ready(function() {
            $("#btnDisableSelected").on('click', function() {
                if(confirm('{!! trans('general.confirm.delete-selected') !!}')) {
                    var $form = $('div#form-container > form').first();
                    $form.attr('action', '{!! route('admin.holidays.disable-selected') !!}');
                    $form.submit();
                }
                return false;
            });
        });
    </script>
@endsection
