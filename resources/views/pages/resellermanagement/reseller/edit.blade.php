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
                <div class="breadcrumb-item active"><a href="{{ route('paket') }}">Paket</a></div>
                <div class="breadcrumb-item">{{ $page_name }}</div>
            </div>
        </div>


        <div class="card">
            <form action="{{ route('resellerdata.update',['id'=>$reseller->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label>Nama Reseller <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $reseller->name}}">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Pemilik Reseller <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('user_id') is-invalid @enderror" name="user_id"
                                id="owner">
                                <option value="">Pilih Pemilik</option>
                                @foreach ($owner as $s)
                                <option value="{{ $s->id }}" {{ $s->id == $reseller->user_id ? 'selected' : '' }}>{{ $s->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Pilih Perusahaan<span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('company_id') is-invalid @enderror" name="company_id"
                                id="company_id">
                                <option value="">Pilih Perusahaan</option>
                                @foreach ($company as $s)
                                <option value="{{ $s->id }}" {{ $s->id == $reseller->company_id ? 'selected' : '' }}>{{ $s->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('company_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>No Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="phone" inputmode="numeric" value="{{ $reseller->phone }}" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label>Nama Bank <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('bank_id') is-invalid @enderror" name="bank_id"
                                id="package">
                                <option value="">Pilih Bank</option>
                                @foreach ($bank as $s)
                                <option value="{{ $s->id }}" {{ $s->id == $reseller->bank_id ? 'selected' : '' }}>{{ $s->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('bank_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>No Rekening <span class="text-danger">*</span></label>
                            <input type="text" name="rekening" inputmode="numeric" value="{{ $reseller->rekening }}"
                                class="form-control @error('rekening') is-invalid @enderror">
                            @error('rekening')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Nama Pemilik Rekening <span class="text-danger">*</span></label>
                            <input type="text" name="owner_rek" value="{{ $reseller->owner_rek }}"
                                class="form-control @error('owner_rek') is-invalid @enderror">
                            @error('owner_rek')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Alamat <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" id="" cols="30" rows="10">{{ $reseller->address }}</textarea>
                            @error('address')
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