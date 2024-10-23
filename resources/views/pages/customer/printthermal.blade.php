@extends('layouts.printthermal')

@section('title', $page_name)

@section('print')
<div class="container-fluid invoice-container" style="padding:0px;max-width: 300px;">
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
        <hr style="margin: 10px;" >
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
                            <td class="text-right">  {{ $customer->nik }} </td>
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
                            <td class="left-col">
                                {{ $subcription->paket->name }} 
                                
                            </td>
                            <td class="text-right">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                        
                        <tr>
                            <td  style="padding-top: 10px;padding-bottom: 5px">Jatuh Tempo
                                <br class=" w-full">&nbsp;&nbsp;&nbsp;&nbsp; ##  {{ \Carbon\Carbon::parse($subcription->end_date)->format('F j, Y') }}
                            </td>
                            <td class="text-right"></td>
                        </tr>
                        <tr>
                            <td class="text-right left-col" style="padding-top: 5px">Sub Total : </td>
                            <td class="text-right" style="padding-top: 5px">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right left-col">Total : </td>
                            <td class="text-right">Rp. {{ number_format($subcription->tagihan) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </main><br>
    <footer class="text-center footer" style="font-style: italic;padding:10px;padding-top:0">
        Pembayaran sudah diterima, terima kasih sudah melunasi tagihan anda !
    </footer>
</div>
<p></p>
@endsection