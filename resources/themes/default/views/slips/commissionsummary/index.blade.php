@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-3'>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('slips/commissionsummary/general.page.index.info-title') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    <ul>
                    <li>Print agent commission summary for selected period.</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class='col-md-9'>
            <div class="box box-primary fa-bg fa-bg-export fa-bg-small">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('slips/commissionsummary/general.page.index.export-title') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    <label class="control-label">Filter</label>
                    <div class="border-decor"></div>
                    {!! Form::open( ['route' => 'slips.commissionsummary.export', 'id' => 'form_export_sales', 'method' => 'GET', 'class' => 'form-horizontal'] ) !!}
                    <div class="form-group">
                        {!! Form::label('month', trans('slips/overriding/general.columns.date'), ['class' => 'control-label col-sm-4'] ) !!}
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">period</span>
                                {!! Form::select('period', ['1' => '1', '2' => '2'], '1', ['class' => 'form-control', 'id' => 'agent_position_id_select',  'style' => "width: 100%", 'tabindex' => 2]) !!}
                                <span class="input-group-addon">month</span>
                                {!! Form::select('month', $month_lists, date('m'), ['class' => 'form-control', 'id' => 'agent_position_id_select',  'style' => "width: 100%", 'tabindex' => 3]) !!}
                                <span class="input-group-addon">year</span>
                                {!! Form::select('year', $year_lists, date('Y'), ['class' => 'form-control', 'id' => 'agent_position_id_select',  'style' => "width: 100%", 'tabindex' => 4]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('agent_id', trans('sales/general.columns.agent_id'), ['class' => 'control-label col-sm-4'] ) !!}
                        <div class="col-sm-8">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('agent_id', ['all' => 'All'] + \App\Agent::getAgents_ForDropDown(), 'all', ['class' => 'form-control select2', 'style' => "width: 100%", 'tabindex' => 5]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-fw fa-child"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('agent_position_id', trans('agents/general.columns.agent_position_id'), ['class' => 'control-label col-sm-4'] ) !!}
                        <div class="col-sm-8">
                            <div class="input-group">
                                {!! Form::select('agent_position_id', ['all' => 'All'] + $agent_position_lists, 'all', ['class' => 'form-control', 'id' => 'agent_position_id_select',  'style' => "width: 100%", 'tabindex' => 2]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-briefcase"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('recalc', 'yes', false) !!} &nbsp; Recalculate
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            {!! Form::submit( trans('general.button.next'), ['class' => 'btn btn-primary btn-submit btn-action', 'id' => 'btn-submit-save', 'tabindex' => 23] ) !!}
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