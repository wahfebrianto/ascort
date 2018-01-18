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
        <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('customers/general.page.show.section-title') }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::model( $customer, ['route' => ['customers.index'], 'method' => 'GET', 'id' => 'form_edit_customer', 'class' => 'form-horizontal'] ) !!}
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_basic" data-toggle="tab" aria-expanded="true">{!! trans('customers/general.tabs.basic') !!}</a></li>
                            <li class=""><a href="#tab_details" data-toggle="tab" aria-expanded="false">{!! trans('customers/general.tabs.details') !!}</a></li>
                            <li class=""><a href="#tab_sales" data-toggle="tab" aria-expanded="false">{!! trans('customers/general.tabs.sales') !!}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_basic">
                                <div class="form-group">
                                    {!! Form::label('NIK', trans('customers/general.columns.NIK'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('NIK', null, ['class' => 'form-control', 'tabindex' => 1, 'disabled']) !!}
                                    </div>
                                </div>

								<div class="form-group">
									{!! Form::label('NPWP', trans('customers/general.columns.NPWP'), ['class' => 'control-label col-sm-2'] ) !!}
									<div class="col-sm-10">
										{!! Form::text('NPWP', null, ['class' => 'form-control', 'tabindex' => 2, 'disabled']) !!}
									</div>
								</div>
								
                                <div class="form-group">
                                    {!! Form::label('name', trans('customers/general.columns.name'), ['class' => 'control-label col-sm-2'] ) !!}
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
                                    {!! Form::label('gender', trans('customers/general.columns.gender'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('gender', trans('general.gender.' . $customer->gender), ['class' => 'form-control', 'tabindex' => 6, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('address', trans('customers/general.columns.address'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('address', null, ['class' => 'form-control', 'tabindex' => 7, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('state', trans('customers/general.columns.state'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('state', null, ['class' => 'form-control', 'tabindex' => 8, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('city', trans('customers/general.columns.city'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('city', null, ['class' => 'form-control', 'tabindex' => 9, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('zipcode', trans('customers/general.columns.zipcode'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('zipcode', null, ['class' => 'form-control', 'tabindex' => 10, 'disabled']) !!}
                                    </div>
                                </div>
                            </div><!-- /.tab-pane -->

                            <div class="tab-pane" id="tab_details">
                              <div class="form-group">
                                  {!! Form::label('cor_address', trans('customers/general.columns.cor_address'), ['class' => 'control-label col-sm-2'] ) !!}
                                  <div class="col-sm-10">
                                      {!! Form::text('cor_address', null, ['class' => 'form-control', 'tabindex' => 11, 'disabled']) !!}
                                  </div>
                              </div>

                              <div class="form-group">
                                  {!! Form::label('cor_state', trans('customers/general.columns.cor_state'), ['class' => 'control-label col-sm-2'] ) !!}
                                  <div class="col-sm-10">
                                      {!! Form::text('cor_state', null, ['class' => 'form-control', 'tabindex' => 12, 'disabled']) !!}
                                  </div>
                              </div>

                              <div class="form-group">
                                  {!! Form::label('cor_city', trans('customers/general.columns.cor_city'), ['class' => 'control-label col-sm-2'] ) !!}
                                  <div class="col-sm-10">
                                      {!! Form::text('cor_city', null, ['class' => 'form-control', 'tabindex' => 13, 'disabled']) !!}
                                  </div>
                              </div>

                              <div class="form-group">
                                  {!! Form::label('cor_zipcode', trans('customers/general.columns.cor_zipcode'), ['class' => 'control-label col-sm-2'] ) !!}
                                  <div class="col-sm-10">
                                      {!! Form::text('cor_zipcode', null, ['class' => 'form-control', 'tabindex' => 14, 'disabled']) !!}
                                  </div>
                              </div>
                            </div><!-- /.tab-pane -->

                            <div class="tab-pane" id="tab_sales">
                                <?=
                                // using Nayjest/Grid table generator based on a data provider
                                (new Grid(
                                        (new GridConfig)
                                                ->setDataProvider(
                                                        $sales_provider
                                                )
                                                ->setName('sales_customer')
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
                                                                ->setName('number')
                                                                ->setLabel('Number')
                                                                ->setSorting(Grid::SORT_DESC)
                                                                ->setCallback(
                                                                        function($val, DataRow $row) {
                                                                            return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $val);
                                                                        }
                                                                )
                                                    ,
                                                        (new FieldConfig)
                                                                ->setName('agent_name')
                                                                ->setLabel('Agent Name')
                                                                ->setCallback(
                                                                        function($val, DataRow $row) {
                                                                            return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $row->getCellValue('agent')['name']);
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
                                                                ->setName('MGI_start_date')
                                                                ->setLabel('MGI Start Date')
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
                                                                ->setName('MGI')
                                                                ->setLabel('MGI')
                                                                ->setCallback(
                                                                        function($val, DataRow $row) {
                                                                            return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), $val);
                                                                        }
                                                                )
                                                    ,
                                                        (new FieldConfig)
                                                                ->setName('FYP')
                                                                ->setLabel('FYP')
                                                                ->setCallback(
                                                                        function($val, DataRow $row) {
                                                                            return \Html::link(route('sales.show', ['id' => $row->getCellValue('id')]), \App\Money::format('%(.2n', $row->getSrc()->FYP));
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


                        </div><!-- /.tab-content -->
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            @if (Auth::getUser()->hasRole('otor'))
                              {!! Html::link(route('approvals.index'), trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                            @else
                              {!! Html::link(route('customers.index'), trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                              <a href="{!! route('customers.edit', $customer->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'><span class="fa fa-fw fa-edit"></span> {{ trans('general.button.edit') }}</a>
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

    <!-- iCheck -->
    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
        });
    </script>
    <div class="modal fade modal-default" tabindex="-1" role="dialog" id="fileModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Image</h4>
                </div>
                <div class="modal-body">
                    {!! Html::image(config('filestorage.customers.id_card_image_dir') . $customer->id_card_image_filename, 'id_card', ['style' => 'width:100%;']) !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
