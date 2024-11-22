@extends('layouts.app')

@section('title', $page_name)

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $page_name }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('customer') }}">Customer</a></div>
                    <div class="breadcrumb-item">{{ $page_name }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <form action="{{ route('customer.renewadd', ['id' => $subs->customer_id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="bg-primary text-white rounded-3 p-3" style="border-radius: 10px">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title">{{ $subs->invoices }}</h4>
                                        <h6>
                                            {{ $subs->status == 1 ? 'Lunas' : 'Belum Bayar' }}
                                        </h6>
                                    </div>

                                    <div class="mt-2">
                                        <h5>{{ $subs->customer->name }}</h5>
                                        <table>
                                            <tr>
                                                <th>Alamat</th>
                                                <td>{{ $subs->customer->address }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nominal</th>
                                                <td>Rp. {{ number_format($subs->tagihan) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Paket</th>
                                                <td>{{ $subs->customer->type == 'reseller' ? $subs->resellerpaket->name : $subs->paket->name }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="form-group col-12 col-md-12">
                                        <label>Rubah Paket Customer <span class="text-danger">*</span></label>

                                        {{-- If customer is a reseller, show ResellerPaket select --}}
                                        @if ($subs->customer->type == 'reseller')
                                            <select
                                                class="form-control select2 @error('reseller_package_id') is-invalid @enderror"
                                                name="reseller_package_id" id="reseller_package">
                                                <option value="">Pilih Paket Reseller</option>

                                                @if ($resellerPaket)
                                                    <option value="{{ $resellerPaket->id }}"
                                                        @if ($subs && $subs->reseller_package_id == $resellerPaket->id) selected @endif>
                                                        {{ $resellerPaket->name }} - Rp.
                                                        {{ number_format($resellerPaket->total) }}
                                                    </option>
                                                @endif
                                            </select>
                                        @else
                                            {{-- If customer is not a reseller, show Package select --}}
                                            <select class="form-control select2 @error('packet_id') is-invalid @enderror"
                                                name="packet_id" id="package">
                                                <option value="">Pilih Paket</option>

                                                @if ($paket)
                                                    <option value="{{ $paket->id }}"
                                                        @if ($subs && $subs->packet_id == $paket->id) selected @endif>
                                                        {{ $paket->name }} - Rp. {{ number_format($paket->price) }}
                                                    </option>
                                                @endif
                                            </select>
                                        @endif

                                        @error('packet_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        @error('reseller_package_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>





                                    <div class="form-group col-12 col-md-6">
                                        <label>Jatuh Tempo <span class="text-danger">*</span></label>
                                        <input type="date" name="end_date" value="{{ $subs->end_date }}"
                                            class="form-control @error('end_date') is-invalid @enderror">
                                        @error('end_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer text-left">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <!-- Page Specific JS File -->

    {{-- <script>
    $(document).ready(function() {
    // Inisialisasi Select2 setelah halaman siap
    $('.select2').select2();
}); --}}

    </script>
@endpush
