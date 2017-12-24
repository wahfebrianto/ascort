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
    @include('partials._head_extra_jstree_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-sm-12 col-md-12 col-lg-9'>

            <!-- Box -->
                <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('admin/agentpos/general.page.index.table-title') }}</h3>
                    &nbsp;|&nbsp;
                    @include('partials._body_top_grid_general_action', ['module_name' => 'admin.agentpos', 'create_title' => trans('admin/agentpos/general.button.create'), 'enabledOnly' => $enabledOnly])

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
                            ->setName('agent_position_index')
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
                                    ->setSorting(Grid::SORT_ASC)
                                    ->setCallback(
                                            function($val, DataRow $row) {
                                                return \Html::link(route('admin.agentpos.show', ['id' => $row->getCellValue('id')]), $val);
                                            }
                                    )
                                ,
                                (new FieldConfig)
                                    ->setName('name')
                                    ->setLabel('Name')
                                    ->setSortable(false)
                                    ->addFilter(
                                        (new FilterConfig)
                                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                                    )
                                    ->setCallback(
                                            function($val, DataRow $row) {
                                                return \Html::link(route('admin.agentpos.show', ['id' => $row->getCellValue('id')]), $val);
                                            }
                                    )
                                ,
                                (new FieldConfig)
                                        ->setName('acronym')
                                        ->setLabel('Acronym')
                                        ->setSortable(false)
                                        ->addFilter(
                                                (new FilterConfig)
                                                        ->setOperator(FilterConfig::OPERATOR_LIKE)
                                        )
                                        ->setCallback(
                                                function($val, DataRow $row) {
                                                    return \Html::link(route('admin.agentpos.show', ['id' => $row->getCellValue('id')]), $val);
                                                }
                                        )
                                ,
                                (new FieldConfig)
                                        ->setName('level')
                                        ->setLabel('Level')
                                        ->setSortable(false)
                                        ->addFilter(
                                                (new FilterConfig)
                                                        ->setOperator(FilterConfig::OPERATOR_LIKE)
                                        )
                                        ->setCallback(
                                                function($val, DataRow $row) {
                                                    return \Html::link(route('admin.agentpos.show', ['id' => $row->getCellValue('id')]), $val);
                                                }
                                        )
                                ,
                                (new FieldConfig)
                                    ->setName('parentname')
                                    ->setLabel('Parent Position')
                                    ->setSortable(false)
                                    ->setCallback(
                                            function($val, DataRow $row) {
                                                $name = empty($row->getCellValue('parent')['name']) ? '-' : $row->getCellValue('parent')['name'];
                                                return \Html::link(route('admin.agentpos.show', ['id' => $row->getCellValue('id')]), $name);
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
                                                        \Html::link(route('admin.agentpos.show', $id), '<span class="fa fa-eye"></span> ' . trans('general.button.view'), [
                                                                'class' => 'btn btn-sm btn-default btn-action',
                                                        ])
                                                ) . ' ';
                                            $editAction = html_entity_decode(
                                                    \Html::link(route('admin.agentpos.edit', $id), '<span class="fa fa-edit"></span> ' . trans('general.button.edit'), [
                                                        'class' => 'btn btn-sm bg-purple btn-action',
                                                    ])
                                                ) . ' ';
                                            if ($row->getCellValue('is_active') == 1) {
                                                $enableAction = html_entity_decode(
                                                    \Html::link(route('admin.agentpos.disable', $id), '<span class="fa fa-trash"></span> ' . trans('general.button.delete'), [
                                                            'class' => 'btn btn-sm btn-danger btn-action',
                                                            'onclick' => 'return confirm(\''. trans('general.confirm.delete', ['ID' => $id]) .'\')',
                                                    ])
                                                ) . ' ';
                                            } else {
                                                $enableAction = html_entity_decode(
                                                    \Html::link(route('admin.agentpos.enable', $id), '<span class="fa fa-share"></span> ' . trans('general.button.restore'), [
                                                            'class' => 'btn btn-sm btn-success btn-action',
                                                            'onclick' => 'return confirm(\''. trans('general.confirm.restore', ['ID' => $id]) .'\')',
                                                    ])
                                                ) . ' ';
                                            }
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
                                                        'href' => route('admin.agentpos.index'),
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
        <div class='col-sm-12 col-md-12 col-lg-3'>
            <!-- Box -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('admin/agentpos/general.page.index.hierarchy') }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    <div id="jstree_menu_div"></div>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


            <!-- Optional bottom section for modals etc... -->
@section('body_bottom')
    @include('partials._body_bottom_grid_general_checkbox', ['module_name' => 'admin.agentpos'])

        <!-- JSTree 3.2.1 -->
    <script src="{{ asset ("/jstree/dist/jstree.min.js") }}"></script>

    <!-- Build and configure JSTree -->
    <script language="JavaScript">
        $('#jstree_menu_div').jstree({
            'core' : {
                'themes' : {
                    'name': 'default',
                    'responsive': true
                },
                'data' : {!! $jstreeJson !!}
            }
        }).bind("loaded.jstree", function (event, data) {
            // Once jsTree is loaded, send command to expend all nodes.
            $(this).jstree("open_all");
        });
    </script>
@endsection
