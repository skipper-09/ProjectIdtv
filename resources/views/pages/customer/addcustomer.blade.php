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
                            <label>Nama Client</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Customer">
                            @error('name')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Mac</label>
                            <input type="text" name="mac" class="form-control" placeholder="Mac STB">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Alamat</label>
                            <input type="text" name="address" class="form-control" placeholder="Alamat">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Ppoe</label>
                            <input type="text" name="ppoe" class="form-control" placeholder="PPOE">
                        </div>
                        <div class="form-group col-12">
                            <label>No Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="No Telepon">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Password</label>
                            <input type="text" name="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Re Password</label>
                            <input type="text" name="password_confirmation" class="form-control" placeholder="Re Password">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Type STB</label>
                            <select class="form-control select2" name="stb_id">
                                <option value="">Pilih Type STB</option>
                                @foreach ($stb as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Area</label>
                            <select class="form-control select2" name="region_id">
                                <option value="">Pilih Area</option>
                                @foreach ($region as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Perusahaan</label>
                            <select class="form-control select2" name="company_id">
                                <option value="">Pilih Perusahaan</option>
                                @foreach ($company as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Status</label>
                            <select class="form-control select2" id="extension" name="is_active">
                                <option value="">Pilih Status</option>
                                <option value="true">Aktif</option>
                                <option value="false">Tidak Aktif</option>
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