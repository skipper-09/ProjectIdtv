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
                <div class="breadcrumb-item active"><a href="{{ route('chanel') }}">Chanel</a></div>
                <div class="breadcrumb-item">{{ $page_name }}</div>
            </div>
        </div>


        <div class="card">
            <form action="{{ route('owner.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label>Nama </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Pemilik" value="{{old('name')}}">
                            @error('name') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>No HP</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="No HP" value="{{old('phone')}}">
                            @error('phone') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Perusahaan</label>
                            <select class="form-control select2 @error('company_id') is-invalid @enderror" name="company_id" >
                                <option value="">Pilih Perusahaan</option>
                                @foreach ($company as $k)
                                <option value="{{ $k->id }}">{{ $k->name }}  </option>
                                @endforeach
                            </select>
                            @error('company_id') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('password') is-invalid @enderror" placeholder="Email" value="{{old('email')}}">
                            @error('email') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Password</label>
                            <input type="text" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                            @error('password') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 ">
                            <label>Alamat </label>
                            <textarea name="address" id="" cols="30" rows="10" class="form-control @error('address') is-invalid @enderror"   data-height="80"></textarea>
                        </div>
                       
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







@endpush