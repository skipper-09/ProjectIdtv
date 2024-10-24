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
        size: auto; /* Mengatur agar halaman sesuai dengan ukuran kertas */
        margin: 0; /* Menghilangkan margin untuk memaksimalkan area cetak */
    }

    body, html {
        margin: 0;
        padding: 0;
        width: 100%;
        background: transparent;
        font-size: 5vw; /* Menggunakan vw agar ukuran font menyesuaikan lebar kertas */
    }

    .container-fluid {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
    }

    .company {
        font-size: 6vw; /* Menggunakan vw agar ukuran font perusahaan menyesuaikan */
        font-weight: 700;
        text-align: center;
    }

    table {
        width: 100%;
        margin: 0 auto;
        border-collapse: collapse;
    }

    table td {
        padding: 0.5vw 1vw; /* Padding menggunakan vw agar menyesuaikan */
        font-size: 5vw; /* Font size menggunakan vw untuk responsivitas */
        word-wrap: break-word;
    }

    .text-right {
        text-align: right;
    }

    .no-print {
        display: none !important; /* Menyembunyikan elemen non-cetak */
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
        <div class="row align-items-center d-flex justify-content-center align-content-center">
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