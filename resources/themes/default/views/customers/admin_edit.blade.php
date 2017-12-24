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
        <div class='col-md-3'>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('customers/general.page.admin_edit.info-title') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    Anda dapat mengubah data pelanggan dengan memilih NIK-nya.
                </div>
            </div>
        </div>
        <div class='col-md-9'>
            <div class="box box-primary fa-bg fa-bg-export fa-bg-small">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('customers/general.page.admin_edit.table-title') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    <br />
                    <label class="control-label">Pilih NIK yang ingin diubah.</label>
                    <div class="border-decor"></div>
                    {!! Form::open( ['route' => 'customers.edit', 'method' => 'GET', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('token', md5(\Session::getId() . 'customer') ) !!}
                    <div class="form-group">
                        {!! Form::label('id', trans('customers/general.columns.NIK'), ['class' => 'control-label col-sm-4'] ) !!}
                        <div class="col-sm-8">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('id', $customer_lists, null, ['class' => 'form-control select2', 'style' => "width: 100%", 'tabindex' => 1]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-fw fa-child"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            {!! Form::submit( trans('general.button.edit'), ['class' => 'btn btn-primary btn-submit btn-action', 'id' => 'btn-submit-save', 'tabindex' => 3] ) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')

    <script src="{{ asset ("/bower_components/admin-lte/select2/js/select2.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
