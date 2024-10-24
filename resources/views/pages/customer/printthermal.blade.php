@extends('layouts.printthermal')

@section('title', $page_name)

@push('style')
<style>
   /* Base styles */
* {
    margin: 0;
    padding: 0;
    line-height: 1.2;
    font-family: 'Arial Narrow', sans-serif;
    color: black;
    -webkit-print-color-adjust: exact !important;
    color-adjust: exact !important;
}

/* Print-specific styles */
@media print {
    /* Default 58mm thermal paper */
    @page {
        size: 58mm auto;
        margin: 2mm;
    }

    /* Responsive container */
    .container-fluid {
        width: 100%;
        max-width: 54mm; /* 58mm - 4mm margins */
        margin: 0 auto;
        padding: 0;
    }

    /* Typography */
    body {
        font-size: 10pt;
        width: 100%;
        margin: 0;
        padding: 0;
    }

    .company {
        font-size: 12pt;
        font-weight: 700;
        text-align: center;
        margin-bottom: 2mm;
    }

    /* Table styles */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .table td {
        padding: 0.5mm 0;
        line-height: 1.2;
        white-space: normal;
        word-wrap: break-word;
    }

    .text-right {
        text-align: right;
    }

    .text-bold {
        font-weight: bold;
    }

    /* Hide non-print elements */
    .no-print {
        display: none !important;
    }

    /* Footer */
    .footer {
        margin-top: 3mm;
        font-size: 9pt;
        text-align: center;
        padding: 2mm 0;
    }
}

/* 80mm thermal paper */
@media print and (min-width: 80mm) {
    @page {
        size: 80mm auto;
        margin: 2mm;
    }

    .container-fluid {
        max-width: 76mm;
    }

    body {
        font-size: 11pt;
    }

    .company {
        font-size: 14pt;
    }
}

/* Generic styles for screen preview */
@media screen {
    .container-fluid {
        width: 58mm;
        margin: 20px auto;
        padding: 10px;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    body {
        background: #f5f5f5;
        font-size: 10pt;
    }

    .table {
        margin-bottom: 10px;
    }

    .table td {
        padding: 2px 0;
    }
}

/* Card styles */
.card {
    border: none;
    margin-bottom: 2mm;
}

.card-body {
    padding: 0;
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
                <div class="table">
                    <table class="table mb-0">
                        <tr>
                            <td>Tanggal</td>
                            <td class="text-right">{{ $payment->format('M/d/Y H:i:s') }}</td>
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
                <div class="table">
                    <table class="table mb-0">
                        <tr>
                            <td class="text-bold ">Item</td>
                            <td class="text-right text-bold">Jumlah</td>
                        </tr>
                        <tr>
                            <td class="">{{ $subcription->paket->name }}</td>
                            <td class="text-right">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;padding-bottom: 5px">Jatuh Tempo<br>
                                &nbsp;&nbsp;&nbsp;&nbsp; ## {{ \Carbon\Carbon::parse($subcription->end_date)->format('F
                                j, Y') }}
                            </td>
                            <td class="text-right"></td>
                        </tr>
                        <tr>
                            <td class="text-right " style="padding-top: 5px">Sub Total:</td>
                            <td class="text-right" style="padding-top: 5px">Rp. {{ number_format($subcription->tagihan)
                                }}</td>
                        </tr>
                        <tr>
                            <td class="text-right ">Total:</td>
                            <td class="text-right">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <footer class="text-center footer" style="font-style: italic; padding: 10px; padding-top: 5px;">
        Pembayaran sudah diterima, terima kasih sudah melunasi tagihan anda!
    </footer>
</div>
@endsection