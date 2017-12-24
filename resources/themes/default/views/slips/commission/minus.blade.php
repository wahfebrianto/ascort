@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-6'>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('slips/commission/general.page.minus.input-title') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    {!! Form::open( ['method' => 'GET', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('agent_id', Input::get('agent_id')) !!}
                    {!! Form::hidden('agent_position_id', Input::get('agent_position_id')) !!}
                    {!! Form::hidden('period', Input::get('period')) !!}
                    {!! Form::hidden('month', Input::get('month')) !!}
                    {!! Form::hidden('year', Input::get('year')) !!}
                    {!! Form::hidden('dist_channel', Input::get('dist_channel')) !!}
                    {!! Form::hidden('recalc', Input::get('recalc')) !!}

                    <div class="form-group">
                        {!! Form::label('agent_id', trans('sales/general.columns.agent_id'), ['class' => 'control-label col-sm-3'] ) !!}
                        <div class="col-sm-9">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('minus_agent_id', $agent_lists, 'all', ['class' => 'form-control select2', 'style' => "width: 100%", 'tabindex' => 1]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-fw fa-child"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('agent_id', 'Minus', ['class' => 'control-label col-sm-3'] ) !!}
                        <div class="col-sm-9">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::number('minus_value', 0, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 2]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-fw fa-minus"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            {!! Form::submit( trans('general.button.add'), ['class' => 'btn btn-primary btn-submit btn-action', 'id' => 'btn-submit-save', 'tabindex' => 3] ) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="box box-primary fa-bg fa-bg-export fa-bg-small">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('slips/commission/general.page.minus.export-title') }}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    <label class="control-label">Agents with Minus Correction</label>
                    <div class="border-decor"></div>
                    {!! Form::open( ['route' => 'slips.commission.export', 'id' => 'form_export_sales', 'method' => 'GET', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('agent_id', Input::get('agent_id')) !!}
                    {!! Form::hidden('agent_position_id', Input::get('agent_position_id')) !!}
                    {!! Form::hidden('period', Input::get('period')) !!}
                    {!! Form::hidden('month', Input::get('month')) !!}
                    {!! Form::hidden('year', Input::get('year')) !!}
                    {!! Form::hidden('recalc', Input::get('recalc')) !!}

                    <table class="table table-stripped table-bordered">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Name</td>
                                <td>Minus</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!Session::has('commission.minus') || count(Session::get('commission.minus')) == 0)
                            <tr>
                                <td colspan="4" class="text-center warning">No minus data</td>
                            </tr>
                        @else
                            @foreach(Session::get('commission.minus') as $key => $value)
                                <tr>
                                    <td>{{ $value['agent']->id }}</td>
                                    <td>{{ $value['agent']->name }}</td>
                                    <td>{{ \App\Money::format('%(.2n', $value['value']) }}</td>
                                    <td><a href="{{ route('slips.commission.delete_minus', ['key' => $key]) }}" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Delete</a></td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    {!! Form::submit( trans('general.button.export'), ['class' => 'btn btn-primary btn-submit btn-action', 'id' => 'btn-submit-save', 'tabindex' => 3] ) !!}

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