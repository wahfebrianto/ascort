@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-3'>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('slips/tax/general.page.index.info-title') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    <ul>
                        <li>Print summary of taxed commission, overriding, and rec fee.</li>
                        <li>Please make sure you already calculated commission, overriding, and rec fee in the period you're going to select.</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class='col-md-9'>
            <div class="box box-primary fa-bg fa-bg-export fa-bg-small">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('slips/tax/general.page.index.export-title') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    <p>{{ trans('slips/tax/general.page.index.export-description') }}</p>
                    <br />
                    <label class="control-label">Filter</label>
                    <div class="border-decor"></div>
                    {!! Form::open( ['route' => 'slips.tax.export', 'id' => 'form_export_sales', 'method' => 'GET', 'class' => 'form-horizontal'] ) !!}
                    <div class="form-group">
                        {!! Form::label('month', trans('slips/overriding/general.columns.date'), ['class' => 'control-label col-sm-4'] ) !!}
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">period</span>
                                {!! Form::select('period', ['1' => '1', '2' => '2'], '1', ['class' => 'form-control', 'id' => 'period_select',  'style' => "width: 100%"]) !!}
                                <span class="input-group-addon">month</span>
                                {!! Form::select('month', $month_lists, date('m'), ['class' => 'form-control', 'id' => 'month_select',  'style' => "width: 100%", 'tabindex' => 2]) !!}
                                <span class="input-group-addon">year</span>
                                {!! Form::select('year', $year_lists, date('Y'), ['class' => 'form-control', 'id' => 'year_select',  'style' => "width: 100%", 'tabindex' => 2]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('agent_id', trans('sales/general.columns.agent_id'), ['class' => 'control-label col-sm-4'] ) !!}
                        <div class="col-sm-8">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('agent_id', ['all' => 'All'] + \App\Agent::getAgents_ForDropDown(), 'all', ['class' => 'form-control select2', 'style' => "width: 100%", 'tabindex' => 1]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-fw fa-child"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <label class="control-label">Export File Type</label>
                    <div class="border-decor"></div>
                    <div class="form-group">
                        {!! Form::label('export_type', 'Export As', ['class' => 'control-label col-sm-4'] ) !!}
                        <div class="col-sm-8">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('export_type', ['pdf' => 'Adobe PDF (.pdf)', 'xlsx' => 'Microsoft Excel 2007+ (.xslx)'], 'all', ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 1]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-files-o"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            {!! Form::submit( trans('general.button.export'), ['class' => 'btn btn-primary btn-submit btn-action', 'id' => 'btn-submit-save', 'tabindex' => 23] ) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body_bottom')
    <script src="{{ asset ("/bower_components/admin-lte/select2/js/select2.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

@endsection