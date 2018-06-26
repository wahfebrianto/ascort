@extends('layouts.pdf')

@section('content')
    @foreach($commissions as $ctr => $commission)
        @if(count($commission->sales) != 0)
            @include('partials._pdf_logo_header')
            <h3><strong>SLIP KOMISI MARKETING</strong></h3>
            <p><em>Tanggal Proses: {{ $commission->process_date }}</em></p>
            <br />
            <table class="info">
                <tr>
                    <td style="width:150px;">NIK</td>
                    <td style="width:10px;">:</td>
                    <td>{{ $commission->agent->NIK }}</td>
                    <td style="width:300px;">&nbsp;</td>
                    <!-- <td style="width:150px;">Dist. Channel</td>
                    <td style="width:10px;">:</td>
                    <td>{{ $commission->agent->dist_channel }}</td> -->
                    <td style="width:150px;">Dilaporkan Kepada</td>
                    <td style="width:10px;">:</td>
                    <td>{{ $commission->agent->parent_name }}</td>
                </tr>
                <tr>
                    <td>Nama Agen</td>
                    <td>:</td>
                    <td>{{ $commission->agent->name }}</td>
                    <td></td>
                    <td>Periode</td>
                    <td>:</td>
                    <td>{{ $start_date." s/d ".$end_date }}</td>
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
                    <th rowspan="2">Nama Investor</th>
                    <th rowspan="2">Tenor</th>
                    <th rowspan="2">Tgl Transaksi</th>
                    <th rowspan="2">Nominal</th>
                    <th colspan="2">Komisi</th>
                </tr>
                <tr>
                    <th>%</th>
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
                        <td >{{ $ctr_sale+1 }}</td>
                        <td >{{ $sale->customer_name }}</td>
                        <td class="text-center">{{ $sale->MGI }}</td>
                        <td class="text-center">{{ $sale->MGI_start_date }}</td>
                        <td class="text-right">{{ \App\Money::format('%(.2n', $sale->nominal) }}</td>
                        <td class="text-center">{{ number_format($sale->agent_commission,2,',','.') }}</td>
                        <td class="text-right">{{ \App\Money::format('%(.2n', $sale->agent_commission_value) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <table class="info">
                <tr>
                    <td style="width:150px;" class="border-bottom-decor"><strong>Transfer ke:</strong></td>
                    <td style="width:10px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="width:10px;">&nbsp;</td>
                    <!-- <td style="width:150px;">Dist. Channel</td>
                    <td style="width:10px;">:</td>
                    <td>{{ $commission->agent->dist_channel }}</td> -->
                    <td style="width:200px;" class="border-bottom-decor"><strong>Pendapatan:</strong></td>
                    <td style="width:10px;">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Bank</td>
                    <td>:</td>
                    <td style="width:200px;">{{ $commission->agent->bank }}</td>
                    <td></td>
                    <td>Total AUM</td>
                    <td>:</td>
                    <td style="width:200px;">{{ \App\Money::format('%(.2n', $commission->total_nominal) }}</td>
                </tr>
                <tr>
                    <td>Cabang</td>
                    <td>:</td>
                    <td style="width:200px;">{{ $commission->agent->bank_branch }}</td>
                    <td></td>
                    <td>Total Komisi Bruto</td>
                    <td>:</td>
                    <td style="width:200px;">{{ \App\Money::format('%(.2n', $commission->gross_commission) }}</td>
                </tr>
                <tr>
                    <td>No Rekening</td>
                    <td>:</td>
                    <td style="width:200px;">{{ $commission->agent->account_number }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Atas Nama</td>
                    <td>:</td>
                    <td style="width:200px;">{{ $commission->agent->name }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <!--table class="info">
                <tr>
                    <td colspan="3" class="border-bottom-decor"><strong>Transfer ke:</strong></td>

                    <td style="width:100px;">&nbsp;</td>

                    <td colspan="3" class="" style="width:30%"></td>

                    <td style="width:100px;">&nbsp;</td>

                    <td colspan="3" class="border-bottom-decor" style="width:30%"><strong>Pendapatan:</strong></td>
                </tr>
                <tr>
                    <td style="width:10%;">Bank</td>
                    <td style="width:1%;">:</td>
                    <td>{{ $commission->agent->bank }}</td>

                    <td>&nbsp;</td>

                    <td style="width:18%;"></td>
                    <td style="width:1%;"></td>
                    <td class="text-right"></td>

                    <td>&nbsp;</td>

                    <td style="width:15%;">Total AUM</td>
                    <td style="width:1%;">:</td>
                    <td class="text-right">{{ \App\Money::format('%(.2n', $commission->total_nominal) }}</td>
                </tr>
                <tr>
                    <td>Cabang</td>
                    <td>:</td>
                    <td>{{ $commission->agent->bank_branch }}</td>

                    <td></td>

                    <td></td>
                    <td></td>
                    <td class=""></td>

                    <td></td>

                    <td>Total Komisi Bruto</td>
                    <td>:</td>
                    <td class="text-right ">{{ \App\Money::format('%(.2n', $commission->gross_commission) }}</td>
                </tr>
                <tr>
                    <td>No. Rekening</td>
                    <td>:</td>
                    <td>{{ $commission->agent->account_number }}</td>

                    <td></td>

                    <td></td>
                    <td></td>
                    <td class="text-right"></td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"></td>
                    
                </tr>
                <tr>
                    <td>Atas Nama</td>
                    <td>:</td>
                    <td>{{ $commission->agent->name }}</td>

                    <td></td>

                    <td></td>
                    <td></td>
                    <td class="text-right"></td>

                    <td></td>

                    <td></td>
                    <td></td>
                    <td class="text-right"></td>
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
                    <td></td>
                    <td></td>
                    <td></td>
                    
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
                    <td></td>
                    <td></td>

                    <td></td>

                    
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

                    <
                    <td>Pajak</td>
                    <td>:</td>
                    <td class="text-right border-bottom-decor">{ { \App\Money::format('%(.2n', $ovr->tax) }}</td>
                    >
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

                    <
                    <td>Pendapatan Setelah Pajak</td>
                    <td>:</td>
                    <td class="text-right">{ { \App\Money::format('%(.2n', $ovr->nett_commission) }}</td>
                    >
                </tr>
            </table-->
            <div class="page-break"></div>
        @endif
    @endforeach
@endsection('content')
