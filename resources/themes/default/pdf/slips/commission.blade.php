@extends('layouts.pdf')

@section('content')
    @foreach($commissions as $ctr => $commission)
        @if(count($commission->sales) != 0)
            @include('partials._pdf_logo_header')
            <h3><strong>Marketing Commission Slip</strong></h3>
            <p><em>Printed on: {{ $commission->process_date }}</em></p>
            <p>Period: {{ $month }} {{ $year }} / {{ $period }}</p>
            <br />
            <table class="info">
                <tr>
                    <td style="width:150px;">NIK</td>
                    <td style="width:10px;">:</td>
                    <td>{{ $commission->agent->NIK }}</td>
                    <td style="width:300px;">&nbsp;</td>
                    <td style="width:150px;">Dist. Channel</td>
                    <td style="width:10px;">:</td>
                    <td>{{ $commission->agent->dist_channel }}</td>
                </tr>
                <tr>
                    <td>Nama Agen</td>
                    <td>:</td>
                    <td>{{ $commission->agent->name }}</td>
                    <td></td>
                    <td>Reporting to</td>
                    <td>:</td>
                    <td>{{ $commission->agent->parent_name }}</td>
                </tr>
                <tr>
                    <td>Jabatan Agen</td>
                    <td>:</td>
                    <td>{{ $commission->agent->agent_position_name }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>NPWP</td>
                    <td>:</td>
                    <td>{{ $commission->agent->NPWP }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <table class="table table-bordered">
                <!--<thead>-->
                <tr>
                    <th rowspan="2">No.</th>
                    <th rowspan="2">No. Penjualan</th>
                    <th rowspan="2">Nama Customer</th>
                    <th rowspan="2">Produk</th>
                    <th rowspan="2">MGI</th>
                    <th rowspan="2">Tgl. MGI Awal</th>
                    <th>Nominal</th>
                    <th colspan="3">Komisi</th>
                </tr>
                <tr>
                    <th>AUMP</th>
                    <th>Selisih</th>
                    <th>Persen</th>
                    <th>Nilai</th>
                </tr>
                <!--</thead>-->
                <tbody>
                @if(count($commission->sales) == 0)
                    <tr>
                        <td colspan="16" class="text-center">No sale</td>
                    </tr>
                @endif

                @foreach($commission->sales as $ctr_sale => $sale)
                    <tr>
                        <td rowspan="2">{{ $ctr_sale+1 }}</td>
                        <td rowspan="2">{{ $sale->number }}</td>
                        <td rowspan="2">{{ $sale->customer_name }}</td>
                        <td rowspan="2">{{ $sale->product_name }}<br />{{ $sale->product_code }}</td>
                        <td rowspan="2" class="text-center">{{ $sale->MGI }}</td>
                        <td rowspan="2" class="text-center">{{ $sale->MGI_start_date }}</td>
                        <td class="text-right">{{ \App\Money::format('%(.2n', $sale->nominal) }}</td>
                        <td rowspan="2">-</td>
                        <td rowspan="2" class="text-center">{{ number_format($sale->agent_commission,2,',','.') }}%</td>
                        <td rowspan="2" class="text-right">{{ \App\Money::format('%(.2n', $sale->agent_commission_value) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">{{ \App\Money::format('%(.2n', $sale->FYP) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <table class="info">
                <tr>
                    <td colspan="3" class="border-bottom-decor" style="width:20%"><strong>Transfer ke:</strong></td>

                    <td style="width:100px;">&nbsp;</td>

                    <td colspan="3" class="border-bottom-decor" style="width:30%"><strong>Penentuan Tarif Pajak:</strong></td>

                    <td style="width:100px;">&nbsp;</td>

                    <td colspan="3" class="border-bottom-decor" style="width:30%"><strong>Pendapatan:</strong></td>
                </tr>
                <tr>
                    <td style="width:10%;">Bank</td>
                    <td style="width:1%;">:</td>
                    <td>{{ $commission->agent->bank }}</td>

                    <td>&nbsp;</td>

                    <td style="width:18%;">Pendapatan YTD Sebelumnya</td>
                    <td style="width:1%;">:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $commission->last_YTD) }}</td>

                    <td>&nbsp;</td>

                    <td style="width:15%;">Total Nominal</td>
                    <td style="width:1%;">:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $commission->total_nominal) }}</td>
                </tr>
                <tr>
                    <td>Cabang</td>
                    <td>:</td>
                    <td>{{ $commission->agent->bank_branch }}</td>

                    <td></td>

                    <td>Pendapatan Saat Ini</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $commission->total_commission) }}</td>

                    <td></td>

                    <td>Total AUMP</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $commission->total_FYP) }}</td>
                </tr>
                <tr>
                    <td>No. Rekening</td>
                    <td>:</td>
                    <td>{{ $commission->agent->account_number }}</td>

                    <td></td>

                    <td>Total YTD Saat Ini</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $commission->last_YTD + $commission->gross_commission) }}</td>

                    <td></td>

                    <td>Total Komisi</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $commission->total_commission) }}</td>
                </tr>
                <tr>
                    <td>Atas Nama</td>
                    <td>:</td>
                    <td>{{ $commission->agent->name }}</td>

                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>

                    <td>Komisi Hold</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $commission->commission_hold) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>
                    <td>Potongan Minus</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $commission->minus * -1) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>

                    <td>Additional</td>
                    <td>:</td>
                    <td class="text-right border-bottom-decor">{{ \App\Money::format('%(.2n', $commission->additional) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>

                    <td>Komisi Bruto</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $commission->gross_commission) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>

                    <!--
                    <td>Pajak</td>
                    <td>:</td>
                    <td class="text-right border-bottom-decor">{ { \App\Money::format('%(.2n', $commission->tax) }}</td>
                    -->
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>

                    <!--
                    <td>Pendapatan Setelah Pajak</td>
                    <td>:</td>
                    <td class="text-right">{ { \App\Money::format('%(.2n', $commission->nett_commission) }}</td>
                    -->
                </tr>
            </table>
            <div class="page-break"></div>
        @endif
    @endforeach
@endsection('content')