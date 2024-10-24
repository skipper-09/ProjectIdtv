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
        size: 50mm auto; /* Sesuaikan lebar kertas dengan ukuran thermal */
        margin: 0; /* Hilangkan margin */
    }

    body, html {
        margin: 0;
        padding: 0;
        width: 50mm; /* Pastikan lebar sesuai dengan kertas thermal */
        background: transparent;
        font-size: 14px; /* Sesuaikan ukuran font agar tidak terlalu besar */
    }

    .container-fluid {
        width: 100%
        max-width: 50mm; /* Batasi lebar konten agar sesuai dengan kertas */
        margin: 0 auto; /* Pusatkan konten */
        padding: 0;
    }

    table {
        width: 100%;
        max-width: 58mm;
        margin: 0 auto;
        border-collapse: collapse; /* Hilangkan jarak antar border */
    }

    table td {
        padding: 2px 5px; /* Sesuaikan padding untuk hasil cetakan yang rapi */
        font-size: 18px; /* Sesuaikan agar mudah terbaca namun tidak terlalu besar */
        word-wrap: break-word; /* Pastikan teks tidak melampaui batas sel */
    }

    .company {
        font-size: 20px; /* Sesuaikan ukuran untuk nama perusahaan */
        font-weight: 700;
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
        <div class="row align-items-center d-flex justify-content-center aligncon">
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