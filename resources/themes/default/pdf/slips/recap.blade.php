@extends('layouts.pdf')

@section('content')
    @include('partials._pdf_logo_header')
<h3><strong>Tax Data</strong></h3>
<p>Tax data printed on: {{ date("d/m/Y H:i:s") }}</p>
<p>Period: {{ $period }} - {{ $month }}/{{ $year }}</p><br />
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 30%">Agent Name</th>
            <th style="width: 20%">Gross Commission</th>
            <th style="width: 20%">Tax</th>
            <th style="width: 20%">Nett Commission</th>
        </tr>
    </thead>
    <tbody>
    @foreach($reports as $report)
        <tr>
            <td>{{ $report->agent->name }}</td>
            <td class="text-right">{{ \App\Money::format('%(.2n', $report->nominal) }}</td>
            <td class="text-right">{{ \App\Money::format('%(.2n', $report->tax) }}</td>
            <td class="text-right">{{ \App\Money::format('%(.2n', $report->nett_commission) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection('content')
