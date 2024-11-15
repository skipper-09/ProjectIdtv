@extends('layouts.printthermal')

@section('title', $page_name)

@push('style')
    <style>
        /* General styling */
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
            margin: 5px 0;
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

        .logo-container {
            text-align: center;
            margin-bottom: 10px;

            display: flex;

            justify-content: center;
            align-items: center;
        }

        .logo-container img {
            max-width: 40mm;
            height: auto;

            align-content: center;
            margin: 0 auto;
        }

        table .sub {
            font-weight: bolder;
        }

        @media print {
            @page {
                size: 58mm auto;
                margin: 0;
            }

            body,
            html {
                margin: 0;
                padding: 0 90px 0 0;
                width: 100%;
                background: transparent;
                font-size: 60px;
            }

            .container-fluid {
                width: 100%;
            }

            .logo-container {
                text-align: center;
                margin-bottom: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .logo-container img {
                max-width: 88mm;
                height: auto;
                align-content: center;
                margin: 10px 0 10px 0;
            }

            .company {
                font-size: 70px;
                font-weight: bolder;
                text-align: center;
            }

            table {
                width: 100%;
                margin: 0;
                padding: 0;
                border-collapse: collapse;
            }

            table .sub {
                font-weight: bolder;
            }

            table td {
                padding: 0;
                margin: 0;
                font-size: 60px;
            }

            .text-right {
                text-align: right;
            }

            .footer {
                font-size: 50px;
                font-style: italic;
            }

            .no-print {
                display: none !important;
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
            <div class="logo-container">
                <img src="{{ asset('img/IDTV.png') }}" class="text-center" alt="Company Logo">
            </div>
            <div class="text-center">
                <b class="company">{{ $customer->company->name ?? $customer->reseller->name }}</b><br>
                {{ $customer->company->address ?? $customer->reseller->address}}
            </div>
            <div style="margin: 10px;"></div>
        </header>
        <main>
            <div class="card">
                <div class="card-body p-0">
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
                       {{-- <tr>
                            <td>ID Pelanggan</td>
                            <td class="text-right">{{ $customer->nik }}</td>
                        </tr>  --}}
                    </table>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tr>
                            <td class="text-bold sub">Item</td>
                            <td class="text-right text-bold sub">Jumlah</td>
                        </tr>
                        <tr>
                            <td>{{ $subcription->paket->name }}</td>
                            <td class="text-right">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">Jatuh
                                Tempo<br>&nbsp;&nbsp;&nbsp;&nbsp;{{ \Carbon\Carbon::parse($subcription->end_date)->format('F j, Y') }}
                            </td>
                            <td class="text-right"></td>
                        </tr>
                        <tr>
                            <td class="text-right">Sub Total:</td>
                            <td class="text-right">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">Total:</td>
                            <td class="text-right">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </main>
        <footer class="text-center footer" style="font-style: italic; padding: 10px;">
            Pembayaran sudah diterima, terima kasih sudah melunasi tagihan anda!
        </footer>
    </div>
@endsection
