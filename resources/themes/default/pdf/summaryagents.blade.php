@extends('layouts.pdf')

@section('content')
<h1>Agents Summary Data</h1>
<p>Agents data printed on: {{ date("d/m/Y H:i:s") }}</p>
<hr />
<table class="table table-stripped">
    <thead>
    <tr>
        <th>Name</td>
        <th>Agent Code</td>
        <th>Position Name</td>
        <th>Total Production</td>
        <th>Total FYP</td>
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
            <td>{{ $agent->agentPositionName }}</td>
            <td class="text-right">{{ \App\Money::format('%(.2n', $agent->total_nominal) }}</td>
            <td class="text-right">{{ \App\Money::format('%(.2n', $agent->total_fyp) }}</td>
        </tr>
        @foreach($agent->childrenRecursive as $a)
            @include('partials._agent_children_recursive', ['agent' => $a, 'with_action' => false, 'level' => 0])
        @endforeach
    @endforeach
    </tbody>
</table>
@endsection('content')
