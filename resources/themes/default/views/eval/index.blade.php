@extends('layouts.master')

@section('content')
    <div class='row'>
        <div class='col-sm-12 col-md-12 col-lg-12'>

            <!-- Box -->
                <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('eval/general.page.index.table-title') }}</h3>
                    &nbsp;|&nbsp;

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    <table class="table-responsive table-striped" width="100%">
                        <thead>
                            <th>Agent Code</th>
                            <th>Name</th>
                            <th>Agent Position</th>
                            <th>Total FYP</th>
                        </thead>

                        <tbody>
                            @foreach($data as $agent)
                                <tr>
                                    <td><a href="{{ url('agents/' . $agent->id) }}">{{ $agent->agent_code }}</a></td>
                                    <td><a href="{{ url('agents/' . $agent->id) }}">{{ $agent->name }}</a></td>
                                    <td><a href="{{ url('agents/' . $agent->id) }}">{{ $agent->agent_position->name }}</a></td>
                                    <td><a href="{{ url('agents/' . $agent->id) }}">{{ \App\Money::format('%(.2n', $agent->TotalFYP) }}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

