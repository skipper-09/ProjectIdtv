@extends('layouts.printthermal')

@section('title', $page_name)

@push('css')
<style>
    /* Batasi lebar maksimum container */
    .container-fluid {
    max-width: 300px !important;
    width: 100% !important;
    margin: 0 auto !important;
    padding: 0 !important;
}
    @media print {
        body {
            margin: 0;
            padding: 0;
        }

        .no-print {
            display: none;
        }

        .container-fluid {
            max-width: 300px;
            width: 100%;
            font-size: 11px; /* Atur font agar sesuai dengan kertas thermal */
            line-height: 1.2;
        }

        .table {
            width: 100%;
            font-size: 11px;
        }

        .table th, .table td {
            padding: 3px 5px;
            border-top: 1px solid #ddd;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            font-size: 10px;
        }
    }
</style>
@endpush
@section('print')
<div class="container-fluid" style="padding:0px; max-width: 300px; width: 100%;">
    <header>
        <div class="row align-items-center no-print d-flex justify-content-center">
            <center><span class="no-print"><br>
            </span><a
                    style="width: 80px;font-weight: bold;padding:1px 5px 1px 5px;color:#333;font-size: 14px;"
                    href="javascript:window.print()" class="btn btn-default no-print"><i class="fa fa-print"></i>
                    Print</a>
                    <a onclick="window.close();"
                    style="width: 80px;font-weight: bold;padding:1px 5px 1px 5px;color:#333;font-size: 14px;"
                    class="btn btn-default no-print"><i class="fa fa-close"></i> Close</a>
                </center>
        </div>
        <hr class="no-print" style="margin-bottom: 10px">
        <div class="row align-items-center d-flex justify-content-center">
            <center><b>{{ $customer->company->name }}</b><br>
                {{ $customer->company->address }}<br></center>
        </div>
        <hr style="margin: 10px;">
    </header>
    <main>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tr>
                            <td>Tanggal</td>
                            <td class="text-right">{{ \Carbon\Carbon::now()->format('M/d/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td>Kasir</td>
                            <td class="text-right">{{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <td>Invoice</td>
                            <td class="text-right"> {{ $subcription->invoices }} </td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td class="text-right"> {{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <td>ID Pelanggan</td>
                            <td class="text-right"> {{ $customer->nik }} </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div style="margin-top: 10px" class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tr>
                            <td class="text-bold left-col">Item</td>
                            <td class="text-right text-bold">Jumlah</td>
                        </tr>
                        <tr>
                            <td class="left-col">{{ $subcription->paket->name }}</td>
                            <td class="text-right">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;padding-bottom: 5px">Jatuh Tempo<br class=" w-full">
                                &nbsp;&nbsp;&nbsp;&nbsp; ## {{ \Carbon\Carbon::parse($subcription->end_date)->format('F j, Y') }}
                            </td>
                            <td class="text-right"></td>
                        </tr>
                        <tr>
                            <td class="text-right left-col" style="padding-top: 5px">Sub Total :</td>
                            <td class="text-right" style="padding-top: 5px">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right left-col">Total :</td>
                            <td class="text-right">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </main><br>
    <footer class="text-center footer" style="font-style: italic; padding:10px; padding-top:0">
        Pembayaran sudah diterima, terima kasih sudah melunasi tagihan anda!
    </footer>
</div>


@endsection
