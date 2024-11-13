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
                <div class="breadcrumb-item active"><a href="{{ route('resellerdata-paket') }}">Reseller Paket</a></div>
                <div class="breadcrumb-item">{{ $page_name }}</div>
            </div>
        </div>
        <div class="card">
            <form action="{{ route('resellerdata-paket.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label>Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Harga Jual <span class="text-danger">*</span></label>
                            <input type="text" inputmode="numeric" name="price" inputmode="numeric"
                                class="form-control @error('price') is-invalid @enderror">
                            @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Reseller <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('reseller_id') is-invalid @enderror"
                                name="reseller_id" id="owner">
                                <option value="">Pilih Reseller</option>
                                @foreach ($reseller as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('reseller_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Paket <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('paket_id') is-invalid @enderror"
                                name="paket_id" id="paket">
                                <option value="">Pilih Paket</option>
                                @foreach ($paket as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} - {{ number_format($s->price) }}
                                </option>
                                @endforeach
                            </select>
                            @error('paket_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Status</label>
                            <select class="form-control select2" id="extension" name="status">
                                <option value="">Pilih Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-left">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

                <div class="px-4 mb-4">
                    <strong>Note</strong><p class="text-muted">Harga Jual Yang diinputkan Akan Ditambahkan Harga Paket Utama Sebagai Pendapatan Reseller</p>
                </div>
            </form>
        </div>

</div>
</section>
</div>
@endsection

@push('scripts')
<!-- JS Libraies -->
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<!-- Page Specific JS File -->
@endpush