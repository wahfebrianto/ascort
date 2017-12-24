@extends('layouts.pdf')

@section('content')
<h4>Customers Data</h4>
<p>Customers data printed on: {{ date("d/m/Y H:i:s") }}</p>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            @foreach($columns as $column)
                <th>{{ trans('customers/general.columns.' . $column) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    @foreach($data as $datum)
        <tr>
            @foreach($datum->getAttributes() as $key => $attribute)
                <td>
                @if ($key == 'gender')
                    {{ trans('general.gender.' . $attribute) }}
                @elseif ($key == 'DOB')
                    {{ $datum->formattedDOB }}
                @elseif ($key == 'id_card_expiry_date')
                    {{ $datum->formattedIdCardExpiryDate }}
                @else
                    {{ $attribute }}
                @endif
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
@endsection('content')
