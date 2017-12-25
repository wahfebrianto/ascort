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
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('agents/general.page.show.section-title') }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::model( $agent, ['route' => ['agents.index'], 'method' => 'GET', 'id' => 'form_edit_customer', 'class' => 'form-horizontal'] ) !!}
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_basic" data-toggle="tab" aria-expanded="true">{!! trans('agents/general.tabs.basic') !!}</a></li>
                            <li class=""><a href="#tab_agent_details" data-toggle="tab" aria-expanded="false">{!! trans('agents/general.tabs.agent_detail') !!}</a></li>
                            <li class=""><a href="#tab_sales" data-toggle="tab" aria-expanded="false">{!! trans('agents/general.tabs.sales') !!}</a></li>
                            <li class=""><a href="#tab_child" data-toggle="tab" aria-expanded="false">{!! trans('agents/general.tabs.child') !!}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_basic">

                                <div class="form-group">
                                    {!! Form::label('agent_code', trans('agents/general.columns.agent_code'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('agent_code', null, ['class' => 'form-control', 'tabindex' => 0, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('NIK', trans('agents/general.columns.NIK'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('NIK', null, ['class' => 'form-control', 'tabindex' => 1, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('name', trans('agents/general.columns.name'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('name', null, ['class' => 'form-control', 'tabindex' => 2, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('phone_number', trans('customers/general.columns.phone_number'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('phone_number', null, ['class' => 'form-control', 'tabindex' => 3, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('handphone_number', trans('customers/general.columns.handphone_number'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('handphone_number', null, ['class' => 'form-control', 'tabindex' => 4, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('email', trans('customers/general.columns.email'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('email', null, ['class' => 'form-control', 'tabindex' => 5, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('birth_place', trans('agents/general.columns.birth_place'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('birth_place', null, ['class' => 'form-control', 'tabindex' => 3, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('DOB', trans('agents/general.columns.DOB'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            {!! Form::text('DOB', $agent->DOB, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'tabindex' => 4, 'disabled']) !!}
                                            <span class = "input-group-addon"><span class="fa fa-fw fa-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('gender', trans('agents/general.columns.gender'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('gender', trans('general.gender.' . $agent->gender), ['class' => 'form-control', 'tabindex' => 4, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('address', trans('agents/general.columns.address'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('address', null, ['class' => 'form-control', 'tabindex' => 7, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('state', trans('agents/general.columns.state'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('state', null, ['class' => 'form-control', 'tabindex' => 8, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('city', trans('agents/general.columns.city'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('city', null, ['class' => 'form-control', 'tabindex' => 9, 'disabled']) !!}
                                    </div>
                                </div>
                            </div><!-- /.tab-pane -->

                            <div class="tab-pane" id="tab_agent_details">

                                <div class="form-group">
                                    {!! Form::label('NPWP', trans('agents/general.columns.NPWP'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('NPWP', null, ['class' => 'form-control', 'tabindex' => 19, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('bank', trans('agents/general.columns.bank'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('bank', null, ['class' => 'form-control', 'tabindex' => 20, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('bank_branch', trans('agents/general.columns.bank_branch'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('bank_branch', null, ['class' => 'form-control', 'tabindex' => 21, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('account_number', trans('agents/general.columns.account_number'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('account_number', null, ['class' => 'form-control', 'tabindex' => 22, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('account_name', trans('agents/general.columns.account_name'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('account_name', null, ['class' => 'form-control', 'tabindex' => 22, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('agent_position_id', trans('agents/general.columns.agent_position_id'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('agent_position_id', $agent_position_lists, null, ['class' => 'form-control', 'id' => 'agent_position_id_select',  'style' => "width: 100%", 'tabindex' => 23, 'disabled']) !!}
                                            <span class="input-group-addon">
                                                <span class="fa fa-fw fa-briefcase"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('parent_id', trans('agents/general.columns.parent_id'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('parent_id', $agent_lists, ($agent->parent_id == null? 'none' : $agent->parent_id), ['class' => 'form-control', 'id' => 'parent_id_select',  'style' => "width: 100%", 'tabindex' => 24, 'disabled']) !!}
                                            <span class="input-group-addon">
                                            <span class="fa fa-fw fa-briefcase"></span>
                                        </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('join_date', trans('agents/general.columns.join_date'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            {!! Form::text('join_date', null, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'tabindex' => 24, 'disabled']) !!}
                                            <span class = "input-group-addon"><span class="fa fa-fw fa-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.tab-pane -->

                            <div class="tab-pane" id="tab_sales">
                                <?=
                                // using Nayjest/Grid table generator based on a data provider
                                (new Grid(
                                        (new GridConfig)
                                                ->setDataProvider(
                                                        $agent_sales_provider
                                                )
                                                ->setName('sales_index')
                                                // ->setCachingTime(10)
                                                ->setPageSize(10)
                                                ->setColumns([
                                                        (new FieldConfig)
                                                                ->setName('id')
                                                                ->setLabel('ID')
                                                                ->setSorting(Grid::SORT_DESC)
                                                                ->setCallback(
                                                                        function($val, DataRow $row) {
                                                                            return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $val);
                                                                        }
                                                                )
                                                    ,
                                                        (new FieldConfig)
                                                                ->setName('customer_name')
                                                                ->setLabel('Customer Name')
                                                                ->setCallback(
                                                                        function($val, DataRow $row) {
                                                                            return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $val);
                                                                        }
                                                                )
                                                    ,
                                                        (new FieldConfig)
                                                                ->setName('product_name')
                                                                ->setLabel('Product')
                                                                ->setCallback(
                                                                        function($val, DataRow $row) {
                                                                            return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $val);
                                                                        }
                                                                )
                                                    ,
                                                        (new FieldConfig)
                                                                ->setName('currency')
                                                                ->setLabel('Currency')
                                                                ->setCallback(
                                                                        function($val, DataRow $row) {
                                                                            return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $val);
                                                                        }
                                                                )
                                                    ,
                                                        (new FieldConfig)
                                                                ->setName('nominal')
                                                                ->setLabel('Nominal')
                                                                ->setCallback(
                                                                        function($val, DataRow $row) {
                                                                            return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), \App\Money::format('%(.2n', $val));
                                                                        }
                                                                )
                                                    ,
                                                        (new FieldConfig)
                                                                ->setName('MGI_month')
                                                                ->setLabel('MGI (Bulan)')
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
                                                                                            \Html::link(route('sales.show', $id), '<span class="fa fa-fw fa-eye"></span> ' . trans('general.button.view'), [
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
                                                                        (new ColumnHeadersRow),
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
                            </div><!-- /.tab-pane -->

                    <div class="tab-pane" id="tab_child">
                        <?=
                        // using Nayjest/Grid table generator based on a data provider
                        (new Grid(
                                (new GridConfig)
                                        ->setDataProvider(
                                                $child_agent_provider
                                        )
                                        ->setName('agents_index')
                                        // ->setCachingTime(10)
                                        ->setPageSize(100)
                                        ->setColumns([
                                                (new FieldConfig)
                                                        ->setName('id')
                                                        ->setLabel('ID')
                                                        ->setSortable(false)
                                                        ->setSorting(Grid::SORT_ASC)
                                                        ->setCallback(
                                                                function($val, DataRow $row) {
                                                                    return \Html::link(route('agents.show', ['id' => $row->getCellValue('id')]), $val);
                                                                }
                                                        )
                                            ,
                                                (new FieldConfig)
                                                        ->setName('NIK')
                                                        ->setLabel('NIK')
                                                        ->setSortable(false)
                                                        ->addFilter(
                                                                (new FilterConfig)
                                                                        ->setOperator(FilterConfig::OPERATOR_LIKE)
                                                        )
                                                        ->setCallback(
                                                                function($val, DataRow $row) {
                                                                    return \Html::link(route('agents.show', ['id' => $row->getCellValue('id')]), $val);
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
                                                                    return \Html::link(route('agents.show', ['id' => $row->getCellValue('id')]), $val);
                                                                }
                                                        )
                                            ,
                                                (new FieldConfig)
                                                        ->setName('agent_positions_name')
                                                        ->setLabel('Agent Position')
                                                        ->setSortable(false)
                                                        ->addFilter(
                                                                (new FilterConfig)
                                                                        ->setOperator(FilterConfig::OPERATOR_LIKE)
                                                        )
                                                        ->setCallback(
                                                                function($val, DataRow $row) {
                                                                    return \Html::link(route('agents.show', ['id' => $row->getCellValue('id')]), $row->getCellValue('agent_position')['name']);
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
                                                                                    \Html::link(route('agents.show', $id), '<span class="fa fa-fw fa-eye"></span> ' . trans('general.button.view'), [
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
                                                (new TFoot)
                                                        ->setComponents([
                                                                (new OneCellRow)
                                                                        ->setComponents([
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
                    </div><!-- /.tab-pane -->

                </div><!-- /.tab-content -->
            </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                          @if (Auth::getUser()->hasRole('otor'))
                            {!! Html::link(route('approvals.index'), trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                          @else
                            {!! Html::link(route('agents.index'), trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('agents.edit', $agent->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'><span class="fa fa-fw fa-edit"></span> {{ trans('general.button.edit') }}</a>
                          @endif

                        </div>
                    </div>

                    {!! Form::close() !!}

                </div><!-- /.box-body -->
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
    @endsection

@section('body_bottom')
    <div class="modal fade modal-default" tabindex="-1" role="dialog" id="fileModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Image</h4>
                </div>
                <div class="modal-body">
                    {!! Html::image(config('filestorage.agents.id_card_image_dir') . $agent->id_card_image_filename, 'id_card', ['style' => 'width:100%;']) !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
