@extends('layouts.pdf')

@section('content')
    @foreach($ovrs as $ctr => $ovr)
        @if(count($ovr->sales) != 0)
            @include('partials._pdf_logo_header')
            <h3><strong>Rec Fee Slip</strong></h3>
            <p><em>Printed on: {{ $ovr->process_date }}</em></p>
            <p>Period: {{ $month }} {{ $year }} / {{ $period }}</p>
            <br />
            <table class="info">
                <tr>
                    <td style="width:150px;">Kode Agen</td>
                    <td style="width:10px;">:</td>
                    <td>{{ $ovr->agent->agent_code }}</td>
                    <td style="width:300px;">&nbsp;</td>
                    <td style="width:150px;">Dist. Channel</td>
                    <td style="width:10px;">:</td>
                    <td>{{ $ovr->agent->dist_channel }}</td>
                </tr>
                <tr>
                    <td>Nama Agen</td>
                    <td>:</td>
                    <td>{{ $ovr->agent->name }}</td>
                    <td></td>
                    <td>Reporting to</td>
                    <td>:</td>
                    <td>{{ $ovr->agent->parent_name }}</td>
                </tr>
                <tr>
                    <td>Jabatan Agen</td>
                    <td>:</td>
                    <td>{{ $ovr->agent->agent_position_name }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>NPWP</td>
                    <td>:</td>
                    <td>{{ $ovr->agent->NPWP }}</td>
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
                    <th rowspan="2">Kode Agen</th>
                    <th rowspan="2">Nama SBD</th>
                    <th rowspan="2">No. Penjualan <br />Produk</th>
                    <th rowspan="2">Nama Customer</th>
                    <th>Nominal</th>
                    <th rowspan="2">Tgl. MGI Awal</th>
                    <th rowspan="2">MGI</th>
                    <th rowspan="2">% OVR</th>
                    <th rowspan="2">Total OVR</th>
                </tr>
                <tr>
                    <th>AUMP</th>
                </tr>
                <!--<thead>-->
                <tbody>
                @if(count($ovr->sales) == 0)
                    <tr>
                        <td colspan="14" class="text-center">No overriding sales</td>
                    </tr>
                @else
                    <?php $ctr_no = 0; ?>
                    @foreach($ovr->sales as $key => $sale)
                        <?php $ctr_no++; ?>
                        <tr>
                            <td rowspan="2">{{ $ctr_no }}</td>
                            <td rowspan="2">{{ $ovr->sales_owner[$key]->agent_code }}</td>
                            <td rowspan="2">{{ $ovr->sales_owner[$key]->name }}</td>
                            <td rowspan="2">{{ $sale->number }} <br />{{ $sale->product_name }} - {{ $sale->product_code }}</td>
                            <td rowspan="2">{{ $sale->customer_name }}</td>
                            <td class="text-right">{{ \App\Money::format('%(.2n', $sale->nominal) }}</td>
                            <td rowspan="2" class="text-center">{{ $sale->MGI_start_date }}</td>
                            <td rowspan="2" class="text-center">{{ $sale->MGI }}</td>
                            <td rowspan="2" class="text-center">{{ $ovr->sales_ovr_percentage[$key] }}%</td>
                            <td rowspan="2" class="text-right">{{ \App\Money::format('%(.2n', $ovr->sales_ovr_value[$key]) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">{{ \App\Money::format('%(.2n', $sale->FYP) }}</td>
                        </tr>
                    @endforeach
                @endif
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
                    <td>{{ $ovr->agent->bank }}</td>

                    <td>&nbsp;</td>

                    <td style="width:18%;">Pendapatan YTD Sebelumnya</td>
                    <td style="width:1%;">:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $ovr->last_YTD) }}</td>

                    <td>&nbsp;</td>

                    <td style="width:15%;">Total Nominal</td>
                    <td style="width:1%;">:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $ovr->total_nominal) }}</td>
                </tr>
                <tr>
                    <td>Cabang</td>
                    <td>:</td>
                    <td>{{ $ovr->agent->bank_branch }}</td>

                    <td></td>

                    <td>Pendapatan Saat Ini</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $ovr->total_commission) }}</td>

                    <td></td>

                    <td>Total AUMP</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $ovr->total_FYP) }}</td>
                </tr>
                <tr>
                    <td>No. Rekening</td>
                    <td>:</td>
                    <td>{{ $ovr->agent->account_number }}</td>

                    <td></td>

                    <td>Total YTD Saat Ini</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $ovr->last_YTD + $ovr->gross_commission) }}</td>

                    <td></td>

                    <td>Total Komisi</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $ovr->total_commission) }}</td>
                </tr>
                <tr>
                    <td>Atas Nama</td>
                    <td>:</td>
                    <td>{{ $ovr->agent->name }}</td>

                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>

                    <td>Komisi Hold</td>
                    <td>:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $ovr->commission_hold) }}</td>
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

                    @if($ovr->minus > 0)
                        <td>Potongan Minus</td>
                        <td>:</td>
                        <td class="text-right border-bottom-decor">{{ \App\Money::format('%(.2n', $ovr->minus) }}</td>
                    @else
                        <td>Additional</td>
                        <td>:</td>
                        <td class="text-right border-bottom-decor">{{ \App\Money::format('%(.2n', $ovr->minus * -1) }}</td>
                    @endif
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
                    <td class="text-right">{{ \App\Money::format('%(.2n', $ovr->gross_commission) }}</td>
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
                    <td class="text-right border-bottom-decor">{ { \App\Money::format('%(.2n', $ovr->tax) }}</td>
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
                    <td class="text-right">{ { \App\Money::format('%(.2n', $ovr->nett_commission) }}</td>
                    -->
                </tr>
            </table>
            <div class="page-break"></div>
        @endif
    @endforeach
@endsection('content')
