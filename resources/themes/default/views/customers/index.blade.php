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
                    <h3 class="box-title">{{ trans('customers//general.page.index.table-title') }}</h3>
                    &nbsp;|&nbsp;
                    @include('partials._body_top_grid_general_action', ['module_name' => 'customers', 'create_title' => trans('customers/general.button.create'), 'enabledOnly' => $enabledOnly])
                    &nbsp;|&nbsp;
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle btn-sm btn-table-head" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="fa fa-download"></span> Export <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a data-toggle="modal" data-target="#exportModal" onclick="document.getElementById('export_type').value='pdf'">
                                    <span class="fa fa fa-file-pdf-o"></span>{{ trans('general.export.pdf') }}
                                </a>
                            </li>
                            <li>
                                <a data-toggle="modal" data-target="#exportModal" onclick="document.getElementById('export_type').value='xlsx'">
                                    <span class="fa fa fa-file-excel-o"></span>{{ trans('general.export.excel') }}
                                </a>
                            </li>
                        </ul>
                    </div>
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
                            ->setName('customers_index')
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
                                                return \Html::link(route('customers.show', ['id' => $row->getCellValue('id')]), $val);
                                            }
                                    )
                                ,
                                (new FieldConfig)
                                    ->setName('NIK')
                                    ->setLabel('NIK')
                                    ->setSortable(true)
                                    ->addFilter(
                                        (new FilterConfig)
                                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                                    )
                                    ->setCallback(
                                            function($val, DataRow $row) {
                                                return \Html::link(route('customers.show', ['id' => $row->getCellValue('id')]), $val);
                                            }
                                    )
                                ,
                                (new FieldConfig)
                                    ->setName('name')
                                    ->setLabel('Name')
                                    ->setSortable(true)
                                    ->addFilter(
                                        (new FilterConfig)
                                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                                    )
                                    ->setCallback(
                                            function($val, DataRow $row) {
                                                return \Html::link(route('customers.show', ['id' => $row->getCellValue('id')]), $val);
                                            }
                                    )
                                ,
                                (new FieldConfig)
                                    ->setName('branchOffice.branch_name')
                                    ->setLabel('Branch Office')
                                    ->setSortable(false)
                                ,
                                (new FieldConfig)
                                    ->setName('action')
                                    ->setLabel('Actions')
                                    ->setSortable(false)
                                    ->setCallback(
                                        function($val, DataRow $row) {
                                            $id = $row->getCellValue('id');
                                            $showAction = html_entity_decode(
                                                        \Html::link(route('customers.show', $id), '<span class="fa fa-eye"></span> ' . trans('general.button.view'), [
                                                                'class' => 'btn btn-sm btn-default btn-action',
                                                        ])
                                                ) . ' ';
                                            $editAction = html_entity_decode(
                                                    \Html::link(route('customers.edit', $id), '<span class="fa fa-edit"></span> ' . trans('general.button.edit'), [
                                                        'class' => 'btn btn-sm bg-purple btn-action',
                                                    ])
                                                ) . ' ';
                                            if ($row->getCellValue('is_active') == 1) {
                                                $enableAction = html_entity_decode(
                                                    \Html::link(route('customers.disable', $id), '<span class="fa fa-trash"></span> ' . trans('general.button.delete'), [
                                                            'class' => 'btn btn-sm btn-danger btn-action',
                                                            'onclick' => 'return confirm(\''. trans('general.confirm.delete', ['ID' => $id]) .'\')',
                                                    ])
                                                ) . ' ';
                                            } else {
                                                $enableAction = html_entity_decode(
                                                    \Html::link(route('customers.enable', $id), '<span class="fa fa-share"></span> ' . trans('general.button.restore'), [
                                                            'class' => 'btn btn-sm btn-success btn-action',
                                                            'onclick' => 'return confirm(\''. trans('general.confirm.restore', ['ID' => $id]) .'\')'
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
                                                        'href' => route('customers.index'),
                                                        'onclick' => 'document.cookie = "customers_index-columns_hider-cookie" + "=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/;"',
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
    <div class="modal fade" tabindex="-1" role="dialog" id="exportModal">
        <div class="modal-dialog">

            <div class="modal-content">
                {!! Form::open( ['route' => 'customers.export', 'id' => 'form_export_customers', 'method' => 'GET', 'class' => 'form-horizontal'] ) !!}
                {!! Form::hidden('type', 'pdf', ['class' => 'form-control', 'id' => 'export_type']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="modal-title" id="myModalLabel">Export options</span>
                </div>
                <div class="modal-body fa-bg fa-bg-export">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Columns to be exported</label>
                            <div class="border-decor"></div>
                            <div class="form-group">
                                @foreach($modelColumns as $column)
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('chkExp[]', $column, true) !!} &nbsp;{!! trans('customers/general.columns.' . $column) !!}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label>Filters</label>
                            <div class="border-decor"></div>
                            <div class="form-group">
                                {!! Form::label('created_at_filter', trans('customers/general.columns.created_at'), ['class' => 'control-label col-sm-4'] ) !!}
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        {!! Form::text('created_at_filter1', '01/01/1970', ['class' => 'datepicker form-control',  'style' => "width: 100%", 'tabindex' => 3]) !!}
                                        <span class="input-group-addon">to</span>
                                        {!! Form::text('created_at_filter2', '01/12/2099', ['class' => 'datepicker form-control',  'style' => "width: 100%", 'tabindex' => 4]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-danger" data-dismiss="modal" href="#"><span class="fa fa-times"></span> Cancel</a>
                    <button type="submit" class="btn btn-primary"><span class="fa fa-download"></span> Export</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

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

    @include('partials._body_bottom_grid_general_checkbox', ['module_name' => 'customers'])
@endsection
