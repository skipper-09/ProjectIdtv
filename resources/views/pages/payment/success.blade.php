@extends('layouts.print')

@section('title', $page_name)

@section('print')
    <div class="container-fluid invoice-container">
        <header>
            <div class="row justify-center align-items-center">
                <center class="align-self-center align-items-center" style="width: 100%">
                    <div class="btn-group btn-group-sm d-print-none"><a style="width: 200px;font-weight: bold"
                            href="javascript:window.print()" class="btn btn-default border text-black-50 shadow-none"><i
                                class="fa fa-print"></i> Simpan Bukti Transaksi</a>
                        {{-- <a style="font-weight: bold;width: 120px;"
                        href="https://billing.csnet.id:1805/rad-customers/print-invoice/3682/thermal"
                        class="btn btn-default border text-black-50 shadow-none"><i class="fa fa-print"></i> POS
                        Print</a> --}}
                        <a id="finish_btn" href="https://google.com" style="width: 120px;font-weight: bold"
                            class="btn btn-default border text-black-50 shadow-none"><i
                                class="fa fa-check"></i> Selesai</a>
                    </div>
                </center>
                <div class="col-sm-7 text-center text-sm-start mb-3 mb-sm-0"><img id="logo" style="max-width:150px;"
                        src="{{ asset('img/IDTV.png') }}" title="topsetting" alt="topsetting" /></div>
                <div class="col-sm-5 text-center text-sm-end">
                    <h4 class="mb-0">INVOICE |
                        @if ($subcription->payment->isEmpty() || $subcription->payment->where('status', '!=', 'paid')->isNotEmpty())
                            <span style="color:red;">BELUM BAYAR</span>
                        @else
                            <span style="font-style:italic;">
                                <i class="fa fa-check"></i>
                                <span style="color:green;">SUDAH BAYAR</span>
                            </span>
                        @endif
                    </h4>
                    <p class="mb-0" style="font-weight: bold">Number : # {{ $subcription->invoices }} </p>
                </div>

            </div>
            <hr>
        </header>
        <main>
            <div class="row">
                <div class="col-sm-6 text-sm-end order-sm-1"><strong>Dibayarkan ke :</strong>
                    <address>
                        {{ $customer->company->name }}<br>
                        {{ $customer->company->address }}<br>
                        {{ $customer->company->phone }}
                    </address>
                </div>
                <div class="col-sm-6 order-sm-0"><strong>Ditagihkan ke :</strong>
                    <address>
                        {{ $customer->name }} | {{ $customer->nik }}<br>
                        {{ $customer->name }} - {{ $customer->address }}<br> {{ $customer->phone }}<br></address>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><span class="fw-600 text-4">Ringkasan Layanan</span></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <td class="col-4"><strong>Paket Langganan</strong></td>
                                    <td class="col-4 text-center"><strong> Jatuh Tempo </strong></td>
                                    <td class="col-4 text-center"><strong>Periode Aktif</strong></td>
                                    <td class="col-4 text-end"><strong>Jumlah</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="text-3"><span class="fw-500">
                                                {{ $subcription->paket->name }}
                                            </span></span></td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($subcription->end_date)->format('F j, Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $subcription->paket->duration }} Bulan
                                    </td>
                                    <td class="text-end">Rp.
                                        {{ number_format($subcription->paket->price + $customer->company->fee_reseller) }}
                                    </td>
                                </tr>

                            </tbody>
                            <tfoot class="card-footer">
                                {{-- <tr>
                                <td colspan="3" class="text-end"><strong>Sub Total :</strong></td>
                                <td class="text-end">
                                    Rp. 125.000,00
                                </td>
                            </tr> --}}

                                <tr>
                                    <td colspan="3" class="text-end border-bottom-0"><strong>Total :</strong></td>
                                    <td class="text-end border-bottom-0">
                                        Rp.
                                        {{ number_format($subcription->paket->price + $customer->company->fee_reseller) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div><br>
            <div id="user_account"></div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td style="border-bottom: 1px;padding: 3px" class="text-center"><strong>Transaction
                                    Date</strong></td>
                            <td style="border-bottom: 1px;padding: 3px" class="text-center"><strong>Gateway</strong></td>
                            <td style="border-bottom: 1px;padding: 3px" class="text-center"><strong>Transaction ID</strong>
                            </td>
                            <td style="border-bottom: 1px;padding: 3px" class="text-center"><strong>Amount</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style=";padding: 3px" class="text-center">
                                {{ \Carbon\Carbon::parse($subcription->start_date)->format('F j, Y') }}</td>
                            <td style=";padding: 3px" class="text-center">{{ $subcription->payment->payment_type }}</td>
                            <td style=";padding: 3px" class="text-center">{{ $subcription->invoices }}</td>
                            <td style=";padding: 3px" class="text-center">Rp.
                                {{ number_format($subcription->paket->price + $customer->company->fee_reseller) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
        <div style="padding-left: 8px;" class="col-md-12"><span id="online_payment"><br>
                <ul style="margin-top: -10px">
                    <li>
                        Pembayaran sudah diterima, terima kasih sudah melunasi tagihan anda<br></li>
                    <li class="w-75">
                        Jika informasi pada bukti pembayaran ini ada kesalahan, silahkan hubungi
                        kami<br><br><b>{{ $customer->company->name }}<br>
                            {{ $customer->company->address }}<br>
                            {{ $customer->company->phone }}</b></li>
                </ul>
            </span></div>
        <hr>
        <footer class="text-center" style="font-size: 13px;font-style: italic;"><strong>NOTE :</strong> This is computer
            generated receipt and does not require physical signature.
        </footer>
    </div>

    {{-- @push('js')
    <script>
        var timer = setTimeout(function() {
            window.location='http://example.com'
        }, 3000);
    </script>
    @endpush --}}
@endsection
