@extends('layouts.pdf')

@section('content')
    @include('partials._pdf_logo_header')
    <h3><strong>Perincian Hasil Investasi</strong></h3>
    <p><em>Printed on: {{ date("d/m/Y H:i:s") }}</em></p>
    <br />
    <table class="info" style="width: 100%;">
        <tr>
            <td colspan="7" class="border-bottom-decor"><strong>Data Customer</strong></td>
        </tr>
        <tr>
            <td style="width: 20%">Nomor</td>
            <td style="width: 10px">:</td>
            <td style="width: 20%">{{ $sale->number }}</td>
            <td style="width: 100px">&nbsp;</td>
            <td style="width: 20%">Tenor</td>
            <td style="width: 10px">:</td>
            <td style="width: 20%">{{ $sale->MGI_month }} bulan</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $sale->customer_name }}</td>
            <td style="width: 100px">&nbsp;</td>
            <td>Tanggal Tenor Awal</td>
            <td>:</td>
            <td>{{ $sale->MGI_start_date }}</td>
        </tr>
        <tr>
            <td>Nominal</td>
            <td>:</td>
            <td>{{ \App\Money::format('%(.2n', $sale->nominal) }}</td>
            <td style="width: 100px">&nbsp;</td>
            <td>Rate</td>
            <td>:</td>
            <td>{{ $sale->interest }}%</td>
        </tr>
    </table>
    <br />
    <p><strong>Bunga</strong></p>
    <div class="border-bottom-decor" style="width: 100%"></div>
    <br />
    <table class="table table-striped table-condensed table-bordered" style="width: 70%;margin-left:15%;">
        <thead>
        <tr>
            <th style="width: 5%;">Nomor</th>
            <th style="width: 15%;">Tanggal Jatuh Tempo</th>
            <th style="width: 10%;">Jumlah Hari</th>
            <th style="width: 15%;">Nominal Bunga</th>
        </tr>
        </thead>
        <?php $ctr = 1; ?>
        @foreach($interests as $interest)
            <tr>
                <td class="text-center">{{ $ctr }}</td>
                <td class="text-center">{{ $interest[0] }}, {{ $interest[1] }}</td>
                <td class="text-center">{{ $interest[2] }} hari</td>
                <td class="text-center">{{ \App\Money::format('%(.2n', $interest[3]) }}</td>
            </tr>
            <?php $ctr++ ?>
        @endforeach
    </table>

@endsection('content')
