@extends('layouts.error')

@section('title', '404')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="page-error">
        <div class="page-inner">
            <h1>Pembayaranmu Belum Selesai</h1>
            <div class="page-description">
                Selesaikan pembayaranmu sekarang
            </div>
            <div class="mt-3">
                <a href="">Lanjutkan Pembayran</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
