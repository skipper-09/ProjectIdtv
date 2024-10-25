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
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        /* Print-specific styles */
        @media print {

            /* Default 58mm thermal paper */
            @page {
                size: auto;
                margin: 4px;
            }

            /* Responsive container */
            .container-fluid {
                width: auto;
                margin: 20px auto;
                padding: 30px;
            }

            /* Logo styles */
            .logo-container {
                text-align: center;
                margin-bottom: 2mm;
            }

            .logo-container img {
                max-width: 40mm;
                /* Adjust based on your logo */
                height: auto;
                margin: 0 auto;
            }

            /* Typography */
            body {
                font-size: 16px;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .company {
                font-size: 20px;
                font-weight: 700;
                text-align: center;
                margin-bottom: 1mm;
            }

            .company-address {
                font-size: 16px;
                text-align: center;
                margin-bottom: 3mm;
            }

            /* Table styles */
            .table {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
            }

            .table td {
                padding: 1mm 0;
                line-height: 1.3;
                white-space: normal;
                word-wrap: break-word;
                font-size: 16px;
            }

            .text-right {
                text-align: right;
            }

            .text-bold {
                font-weight: 600;
            }

            /* Footer */
            .footer {
                margin-top: 4mm;
                font-size: 16px;
                text-align: center;
                padding: 2mm 0;
                font-style: italic;
            }

            /* Hide non-print elements */
            .no-print {
                display: none !important;
            }
        }

        /* Generic styles for screen preview */
        @media screen {
            .container-fluid {
                width: 88mm;
                margin: 20px auto;
                padding: 10px;
                border: 1px solid #ccc;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            body {
                background: #f5f5f5;
                font-size: 12pt;
            }

            .logo-container img {
                max-width: 150px;
                height: auto;
                margin: 0 auto;
            }

            .table {
                margin-bottom: 10px;
            }

            .table td {
                padding: 3px 0;
                font-size: 12pt;
            }
        }

        /* Card styles */
        .card {
            border: none;
            margin-bottom: 3mm;
        }

        .card-body {
            padding: 0;
        }

        /* Additional styles for better spacing */
        .section-separator {
            border-top: 1px dashed #000;
            margin: 2mm 0;
        }
    </style>
@endpush

@section('print')
    <div class="container-fluid">
        <header>
            <!-- Print/Close buttons for preview -->
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

            <!-- Logo and Company Info -->
            <div class="logo-container mb-3  d-flex justify-content-center">
                <img src="{{ asset('img/IDTV.png') }}" alt="Company Logo">
            </div>
            <div class="company text-center font-weight-bold">{{ $customer->company->name }}</div>
            <div class="company-address text-center">{{ $customer->company->address }}</div>
        </header>

        <main>
            <!-- Customer Info Section -->
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

            <div class="section-separator"></div>

            <!-- Item Details Section -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table">
                        <table class="table mb-0">
                            <tr>
                                <td class="text-bold">Item</td>
                                <td class="text-right text-bold">Jumlah</td>
                            </tr>
                            <tr>
                                <td>{{ $subcription->paket->name }}</td>
                                <td class="text-right">Rp.{{ number_format($subcription->tagihan) }}</td>
                            </tr>
                            <tr>
                                <td style="padding-top: 3mm; padding-bottom: 2mm">
                                    Jatuh Tempo<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;##
                                    {{ \Carbon\Carbon::parse($subcription->end_date)->format('F j, Y') }}
                                </td>
                                <td class="text-right"></td>
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
            </div>
        </main>

        <div class="section-separator"></div>

        <!-- Footer -->
        <footer class="footer text-center">
            Pembayaran sudah diterima, terima kasih sudah melunasi tagihan anda!
        </footer>
    </div>
@endsection
