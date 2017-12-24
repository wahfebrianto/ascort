@extends('layouts.pdf')
<?php 
	$gt_commission = 0;
	$gt_tax = 0;
	$gt_nett = 0;
?>
@section('content')
    @include('partials._pdf_logo_header')
	<h3><strong>Marketing Commission Slip (Summary)</strong></h3>
	<p><em>Printed on: {{ date('d/m/Y h:i:s') }}</em></p>
	<p>Period: {{ $month }} {{ $year }} / {{ $period }}</p>
	<br />
	<table class="table table-stripped table-bordered">
		<tr>
			<th style="width:3%;">No.</td>
			<th style="width:5%;">Kode Agen</td>
			<th style="width:5%;">Dist. Channel</td>
			<th style="width:10%;">Nama Agen</td>
			<th style="width:10%;">Jabatan Agen</td>
			<th style="width:10%;">Total Komisi</td>
			<!--
			<th style="width:10%;">Pajak</td>
			<th style="width:10%;">Pendapatan Setelah Pajak</td>
			-->
			<th style="width:5%;">Bank</td>
			<th style="width:5%;">Cabang</td>
			<th style="width:5%;">No. Rekening</td>
			<th style="width:10%;">Atas Nama</td>
		</tr>
    @foreach($commissions as $ctr => $commission)
		@if($commission->gross_commission != 0)
			<tr>
				<td>{{ $ctr }}</td>
				<td>{{ $commission->agent->agent_code }}</td>
				<td>{{ $commission->agent->dist_channel }}</td>
				<td>{{ $commission->agent->name }}</td>
				<td>{{ $commission->agent->agent_position_name }}</td>
				<td class="text-right">{{ \App\Money::format('%(.2n', $commission->gross_commission) }}</td>
				<!--
				<td class="text-right">{ { \App\Money::format('%(.2n', $commission->tax) }}</td>
				<td class="text-right">{ { \App\Money::format('%(.2n', $commission->nett_commission) }}</td>
				-->
				<td>{{ $commission->agent->bank }}</td>
				<td>{{ $commission->agent->bank_branch }}</td>
				<td>{{ $commission->agent->account_number }}</td>
				<td>{{ $commission->agent->name }}</td>
				<?php
					$gt_commission += $commission->gross_commission;
					$gt_tax += $commission->tax;
					$gt_nett += $commission->nett_commission;
				?>
			</tr>
		@endif
    @endforeach
		<tr>
			<td colspan="5" class="text-right">Total</td>
			<td class="text-right">{{ \App\Money::format('%(.2n', $gt_commission) }}</td>
			<!--<td class="text-right">{ { \App\Money::format('%(.2n', $gt_tax) }}</td>
			<td class="text-right">{ { \App\Money::format('%(.2n', $gt_nett) }}</td>-->
			<td colspan="4" class="text-right"></td>
		</tr>
	</table>
@endsection('content')
