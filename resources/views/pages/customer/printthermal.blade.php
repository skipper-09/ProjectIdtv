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
                size: 88mm auto;
                /* Tentukan lebar kertas 58mm dan tinggi menyesuaikan */
                margin: 0;
                /* Hapus semua margin default */
            }

            /* Kontainer utama */
            .container-fluid {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            /* Typography */
            body {
                font-size: 20px;
                /* Sesuaikan ukuran font agar pas di kertas termal */
                width: 80mm;
                margin: 0;
                padding: 0;
                width: 100%;
            }

            .logo-container {
                text-align: center;
                margin-bottom: 5px;
                /* Sesuaikan margin */
            }

            .logo-container img {
                max-width: 40mm;
                height: auto;
                margin: 0 auto;
            }

            .company {
                font-size: 30px;
                font-weight: bold;
                text-align: center;
                margin-bottom: 2px;
            }

            .company-address {
                font-size: 30px;
                text-align: center;
                margin-bottom: 4px;
            }


            .table {
                width: 100%;
                border-collapse: collapse;
                margin: 10px,
            }

            .table td {
                padding: 10px 0;
                line-height: 1.2;
                white-space: nowrap;
                word-wrap: break-word;
                font-size: 30px;

            }

            .text-right {
                text-align: right;
            }

            .text-bold {
                font-weight: bold;
            }


            .footer {
                margin-top: 5px;
                font-size: 30px;
                text-align: center;
                font-style: italic;
            }


            .no-print {
                display: none !important;
            }
        }




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
                                <td>
                                    Jatuh Tempo<br>
                                    ##
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
