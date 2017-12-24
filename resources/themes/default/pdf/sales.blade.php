@extends('layouts.pdf')

@section('content')
<h4>Sales Data</h4>
<p>Sales data printed on: {{ date("d/m/Y H:i:s") }}</p>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            @foreach($columns as $column)
                @if($column == 'agent_commission')
                    <th style="width:20px">Agent Comm.</th>
                @elseif($column == 'mgi_start_date')
                    <th style="width:50px">MGI Start</th>
                @else
                    <th>{{ trans('sales/general.columns.' . $column) }}</th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
    @foreach($data as $datum)
        <tr>
            @foreach($datum->getAttributes() as $key => $attribute)
                @if ($key == 'agent_id')
                    <td>{{ $datum->agentName }}</td>
                @elseif ($key == 'product_id')
                    <td>{{ $datum->productName }}</td>
                @elseif ($key == 'customer_id')
                    <td>{{ $datum->customerName }}</td>
                @elseif ($key == 'agent_commission')
                    <td>{{ $attribute }}%</td>
                @elseif ($key == 'nominal')
                    <td class="text-right">{{ \App\Money::format('%(.2n', $attribute) }}</td>
                @else
                    <td>{{ $attribute }}</td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
@endsection('content')
