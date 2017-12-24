@extends('layouts.pdf')

@section('content')
@foreach($agents as $key => $value)
    @if(count($sales[$value->id]) != 0)
        @include('partials._pdf_logo_header')
        <h4>Sales MGI Due Date Notification</h4>
        <p>Printed on: {{ date("d/m/Y H:i:s") }}</p>
        <p>Viewing sales on due between: {{ $date1->format('d/m/Y') }} and {{ $date2->format('d/m/Y') }}</p>
        <h5>{{ $value->name }} ({{ $value->agentPositionName }})</h5>
        <table class="table table-striped table-condensed table-bordered">
            <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 15%;">Agent Name</th>
                <th style="width: 15%;">Customer Name</th>
                <th style="width: 10%;">MGI Start Date</th>
                <th style="width: 10%;">Due Date</th>
                <th style="width: 10%;">Nominal</th>
                <th style="width: 5%;">MGI</th>
                <th style="width: 10%;">FYP</th>
            </tr>
            </thead>
            @foreach($sales[$value->id] as $sale)
                <tr>
                    <td class="text-center">{{ $sale->id }}</td>
                    <td>{{ $sale->agentName }}</td>
                    <td>{{ $sale->customer->name }}</td>
                    <td class="text-center">{{ $sale->MGI_start_date }}</td>
                    <td class="text-center">{{ $sale->dueDate }}</td>
                    <td class="text-right">{{ $sale->formattedNominal }}</td>
                    <td>{{ $sale->MGI }}</td>
                    <td class="text-right">{{ $sale->formattedFYP }}</td>
                </tr>
            @endforeach
            <div class="page-break"></div>
        </table>
    @endif
@endforeach

@endsection('content')
