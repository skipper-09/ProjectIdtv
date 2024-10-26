@extends('layouts.printthermal')

@section('title', $page_name)

@push('style')
<style>
    /* Base styles */
    * {
        margin: 0;
        padding: 0;
        line-height: 1.3;
        font-family: 'Arial Narrow', sans-serif;
        color: black;
    }

    /* Print-specific styles */
    @media print {
        @page {
            size: auto;
            margin: 0;
        }

        .container-fluid {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 12pt;
            margin: 0;
            width: 100%;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo-container img {
            max-width: 40mm;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .company {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 10pt;
            text-align: center;
            margin-bottom: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .table td {
            padding: 5px 0;
            line-height: 1.2;
            font-size: 10pt;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .footer {
            margin-top: 10px;
            font-size: 10pt;
            text-align: center;
            font-style: italic;
        }

        .no-print {
            display: none !important;
        }
    }

    /* Screen-specific styles */
    @media screen {
        .container-fluid {
            width: 88mm;
            margin: 10px auto;
            padding: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        body {
            background: #f5f5f5;
            font-size: 10pt;
        }

        .logo-container img {
            max-width: 150px;
            height: auto;
        }

        .table td {
            padding: 3px 0;
        }
    }

    /* Additional spacing for sections */
    .section-separator {
        border-top: 1px dashed #000;
        margin: 8px 0;
    }
</style>
@endpush

@section('print')
<div class="container-fluid">
    <header>
        <!-- Print/Close buttons for preview -->
        <div class="row no-print d-flex justify-content-center">
            <a href="javascript:window.print()" class="btn btn-default no-print" style="width: 80px; font-weight: bold; font-size: 14px;">
                <i class="fa fa-print"></i> Print
            </a>
            <a onclick="window.close();" class="btn btn-default no-print" style="width: 80px; font-weight: bold; font-size: 14px;">
                <i class="fa fa-close"></i> Close
            </a>
        </div>
        <hr class="no-print" style="margin-bottom: 10px;">

        <!-- Logo and Company Info -->
        <div class="logo-container">
            <img src="{{ asset('img/IDTV.png') }}" alt="Company Logo">
        </div>
        <div class="company">{{ $customer->company->name }}</div>
        <div class="company-address">{{ $customer->company->address }}</div>
    </header>

    <main>
        <!-- Customer Info Section -->
        <div class="card">
            <div class="card-body p-0">
                <table class="table">
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

        <div class="section-separator"></div>

        <!-- Item Details Section -->
        <div class="card">
            <div class="card-body p-0">
                <table class="table">
                    <tr>
                        <td class="text-bold">Item</td>
                        <td class="text-right text-bold">Jumlah</td>
                    </tr>
                    <tr>
                        <td>{{ $subcription->paket->name }}</td>
                        <td class="text-right">Rp.{{ number_format($subcription->tagihan) }}</td>
                    </tr>
                    <tr>
                        <td>Jatuh Tempo</td>
                        <td class="text-right">{{ \Carbon\Carbon::parse($subcription->end_date)->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-right text-bold">Sub Total:</td>
                        <td class="text-right">Rp.{{ number_format($subcription->tagihan) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right text-bold">Total:</td>
                        <td class="text-right text-bold">Rp.{{ number_format($subcription->tagihan) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </main>

    <div class="section-separator"></div>

    <!-- Footer -->
    <footer class="footer">
        Pembayaran sudah diterima, terima kasih sudah melunasi tagihan anda!
    </footer>
</div>
@endsection
