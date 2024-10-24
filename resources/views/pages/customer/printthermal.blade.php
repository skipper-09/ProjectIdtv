@extends('layouts.printthermal')

@section('title', $page_name)

@push('style')
<style>
    /* General styling for all devices */
    * {
        line-height: 1.5;
        font-family: 'Arial Narrow', sans-serif;
        color: black;
        -webkit-print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    .text-right {
        text-align: right;
    }

    .card {
        border-radius: 0;
        border: none;
    }

    .table {
        width: 100%;
        border-bottom-width: none;
    }

    .table> :not(caption)>*>* {
        padding-top: 0;
        padding-bottom: 0;
    }

    .container-fluid {
        width: max-content;
    }

    @media print {
        @page {
            size: 44mm auto;
            /* Set the page size to 44mm width with auto height */
            margin: 0;
            /* Remove all margins */
        }

        body,
        html {
            margin: 0;
            padding: 0;
            width: 44mm;
            /* Set the content width to match the paper width */
            background: transparent;
            font-size: 40px;
            /* Set a suitable font size */
        }

        .company {
            font-size: 45px;
            font-weight: 700;
        }

        .container-fluid {
            width: 100%;
            margin: 0 auto;
            /* Center the content */
            padding: 0;
        }

        table {
            width: 100%;
            margin: 0 auto;
        }

        table td {
            padding: 2px 2px;
            /* Minimum padding for thermal printers */
            font-size: 40px;
            /* Ensure font size is readable */
            word-wrap: break-word;
            /* Ensure text wraps within the table cells */
        }

        .no-print {
            display: none !important;
            /* Hide non-printable elements */
        }
    }
</style>
@endpush

@section('print')
<div class="container-fluid">
    <header>
        <div class="row align-items-center no-print d-flex justify-content-center">
            <center>
                <a href="javascript:window.print()" class="btn btn-default no-print"
                    style="width: 80px; font-weight: bold; padding: 1px 5px; color: #333; font-size: 14px;">
                    <i class="fa fa-print"></i> Print
                </a>
                <a onclick="window.close();" class="btn btn-default no-print"
                    style="width: 80px; font-weight: bold; padding: 1px 5px; color: #333; font-size: 14px;">
                    <i class="fa fa-close"></i> Close
                </a>
            </center>
        </div>
        <hr class="no-print" style="margin-bottom: 10px;">
        <div class="row align-items-center d-flex justify-content-center">
            <center>
                <b class="company">{{ $customer->company->name }}</b><br>
                {{ $customer->company->address }}<br>
            </center>
        </div>
        <div style="margin: 10px;"></div>
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
                            <td class="text-right">{{ $subcription->invoices }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td class="text-right">{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <td>ID Pelanggan</td>
                            <td class="text-right">{{ $customer->nik }}</td>
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
                                &nbsp;&nbsp;&nbsp;&nbsp; ## {{ \Carbon\Carbon::parse($subcription->end_date)->format('F
                                j, Y') }}
                            </td>
                            <td class="text-right"></td>
                        </tr>
                        <tr>
                            <td class="text-right left-col" style="padding-top: 5px">Sub Total:</td>
                            <td class="text-right" style="padding-top: 5px">Rp. {{ number_format($subcription->tagihan)
                                }}</td>
                        </tr>
                        <tr>
                            <td class="text-right left-col">Total:</td>
                            <td class="text-right">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <footer class="text-center footer" style="font-style: italic; padding: 10px; padding-top: 0;">
        Pembayaran sudah diterima, terima kasih sudah melunasi tagihan anda!
    </footer>
</div>
@endsection