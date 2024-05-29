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


        <div class="card">
            <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label>Nama Client<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Nama Customer">
                            @error('name')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Mac<span class="text-danger">*</span></label>
                            <input type="text" name="mac" class="form-control @error('mac') is-invalid @enderror"
                                placeholder="Mac STB">
                            @error('mac')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Alamat</label>
                            <input type="text" name="address" class="form-control" placeholder="Alamat">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Ppoe<span class="text-danger">*</span></label>
                            <input type="text" name="ppoe" class="form-control @error('ppoe') is-invalid @enderror"
                                placeholder="PPOE">
                            @error('ppoe')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>No Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="No Telepon">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Username<span class="text-danger">*</span></label>
                            <input type="text" name="username"
                                class="form-control @error('username') is-invalid @enderror" placeholder="Username">
                            @error('username')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Password<span class="text-danger">*</span></label>
                            <input type="text" name="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                            @error('password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Konfirmasi Password<span class="text-danger">*</span></label>
                            <input type="text" name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                placeholder="Konfirmasi Password">
                            @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Type STB<span class="text-danger">*</span></label>
                            <select class="form-control select2" name="stb_id">
                                <option value="">Pilih Type STB</option>
                                @foreach ($stb as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Area<span class="text-danger">*</span></label>
                            <select class="form-control select2" name="region_id">
                                <option value="">Pilih Area</option>
                                @foreach ($region as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Perusahaan<span class="text-danger">*</span></label>
                            <select class="form-control select2" name="company_id">
                                <option value="">Pilih Perusahaan</option>
                                @foreach ($company as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Status<span class="text-danger">*</span></label>
                            <select class="form-control select2" id="extension" name="is_active">
                                <option value="">Pilih Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-left">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
            </form>
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
    $(document).ready(function($) {
            $('#extension').change(function() {
                var selected = $('#extension').val()
                if (selected == 'mpd') {
                    $('#security').removeClass('d-none');
                    $('#security-type').removeClass('d-none');
                } else {
                    $('#security').addClass('d-none');
                    $('#security-type').addClass('d-none');
                }
            });
        });
</script> --}}
@endpush