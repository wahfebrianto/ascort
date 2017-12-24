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
    <link href="{{ asset("/treetable/css/jquery.treetable.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/treetable/css/jquery.treetable.theme.default.css") }}" rel="stylesheet" type="text/css" />

    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-sm-12 col-md-12'>
            <!-- Box -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('agents/general.page.summary.hierarchy-title') }}</h3>
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
                            <!--
                            <li>
                                <a data-toggle="modal" data-target="#exportModal" onclick="document.getElementById('export_type').value='xlsx'">
                                    <span class="fa fa fa-file-excel-o"></span>{{ trans('general.export.excel') }}
                                </a>
                            </li>
                            -->
                        </ul>
                    </div>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">
                    {!! Form::open( ['route' => 'agents.summary', 'method' => 'GET'] ) !!}

                    <table id="treetable" class="table table-stripped">
                        <thead>
                            <tr>
                                <th colspan="6">
                                    <div style="display: inline-block;" class="typo-all-caps">Table Tools <i class="fa fa-chevron-right"></i></div>
                                    <a class="btn btn-danger btn-sm pull-right" href="{{ route('agents.summary') }}" style="margin-left: 3px;">
                                        <i class="glyphicon glyphicon-refresh"></i> Reset Page and Filter
                                    </a>
                                    &nbsp;
                                    <button class="btn bg-navy btn-sm pull-right" style="margin-left: 3px;">
                                        <i class="fa fa-search"></i> Refresh and Apply Filter
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th>Name</td>
                                <th>Agent Code</td>
                                <th>Position Name</td>
                                <th>Total Nominal</td>
                                <th>Total FYP</td>
                                <th>Action</td>
                            </tr>
                            <tr class="filter">
                                <td>{!! Form::text('name', Input::get('name'), ['class' => 'form-control input-sm', 'tabindex' => 1]) !!}</td>
                                <td>{!! Form::text('agent_code', Input::get('agent_code'), ['class' => 'form-control input-sm', 'tabindex' => 2]) !!}</td>
                                <td>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($agents as $agent)
                            <tr data-tt-id="{{ $agent->id }}"
                            @if($agent->parent_id != null)
                                data-tt-parent-id="{{ $agent->parent_id }}"
                            @endif
                            >
                                <td>{{ $agent->name }}</td>
                                <td>{{ $agent->agent_code }}</td>
                                <td>{{ $agent->agent_position_name }}</td>
                                <td class="text-right">{{ \App\Money::format('%(.2n', $agent->total_nominal) }}</td>
                                <td class="text-right">{{ \App\Money::format('%(.2n', $agent->total_fyp) }}</td>
                                <td><a class="btn btn-action btn-default btn-sm" href="{{ route('agents.show', ['id' => $agent->id]) }}"><i class="fa fa-eye"></i> View</a></td>
                            </tr>
                            @foreach($agent->childrenRecursive as $a)
                                @include('partials._agent_children_recursive', ['agent' => $a, 'with_action' => true, 'level' => 1])
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                    <input type="submit" style="visibility: hidden;" />
                    {!! Form::close() !!}
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


            <!-- Optional bottom section for modals etc... -->
@section('body_bottom')
    <div class="modal fade" tabindex="-1" role="dialog" id="exportModal">
        <div class="modal-dialog">

            <div class="modal-content">
                {!! Form::open( ['route' => 'agents.summary_export', 'id' => 'form_export_agents', 'method' => 'GET', 'class' => 'form-horizontal'] ) !!}
                {!! Form::hidden('type', 'pdf', ['class' => 'form-control', 'id' => 'export_type']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="modal-title" id="myModalLabel">Export options</span>
                </div>
                <div class="modal-body fa-bg fa-bg-export fa-bg-small">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Filters</label>
                            <div class="border-decor"></div>
                            <p>{{ trans('agents/general.page.summary.exportdetail') }}</p>
                            <div class="form-group">
                                {!! Form::label('agent_id', trans('agents/general.columns.name'), ['class' => 'control-label col-sm-4'] ) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('agent_id', $agent_lists, 'all', ['class' => 'form-control select2', 'id' => 'agent_position_id_select',  'style' => "width: 100%", 'tabindex' => 4]) !!}
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

    <script src="{{ asset ("/treetable/jquery.treetable.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/select2/js/select2.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $("#treetable").treetable({
                initialState: 'expanded',
                expandable: true,
                indent: '10'
            });
        });
        $('.select2').select2();
    </script>
@endsection
