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
      <h1>{{$page_name}}</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{route('paket')}}">Paket</a></div>
        <div class="breadcrumb-item">{{$page_name}}</div>
      </div>
    </div>


    <div class="card">
      <form action="{{ route('paket.update',['id'=>$paket->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{ method_field('PUT') }}
        <div class="card-body">
          <div class="row">
            <div class="form-group col-12 col-md-6">
              <label>Nama Paket <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{$paket->name}}">
              @error('name')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="form-group col-12 col-md-6">
              <label>Harga Paket <span class="text-danger">*</span></label>
              <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                value="{{$paket->price}}">
              @error('price')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="form-group col-12 col-md-6">
              <label>Durasi Paket <span class="text-danger">*</span></label>
              <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror"
                value="{{$paket->duration}}">
              @error('duration')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="form-group col-12 col-md-6">
              <label>Perusahaan <span class="text-danger">*</span></label>
              <select class="form-control select2 @error('company_id') is-invalid @enderror" name="company_id"
                id="company">
                <option value="">Pilih Perusahaan</option>
                @foreach ($company as $s)
                <option value="{{ $s->id }}" {{ $s->id == $paket->company_id ? 'selected':'' }}>{{ $s->name }}
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
              <label>Tipe Paket <span class="text-danger">*</span></label>
              <select class="form-control select2 @error('type') is-invalid @enderror" name="type" id="type">
                <option value="">Pilih Tipe Paket</option>
                <option value="reseller" {{ $paket->type_paket == 'reseller' ? 'selected':'' }}>Reseller
                </option>
                <option value="main" {{ $paket->type_paket == 'main' ? 'selected':'' }}>Paket Utama
                </option>
              </select>
              @error('type')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
              @enderror
            </div>
            <div class="form-group col-12 col-md-6">
              <label>Status <span class="text-danger">*</span></label>
              <select class="form-control select2 @error('status') is-invalid @enderror" name="status" id="status">
                <option value="">Pilih Status</option>
                <option value="1" {{ $paket->status == 1 ? 'selected':'' }}>Aktif
                </option>
                <option value="0" {{ $paket->status == 0 ? 'selected':'' }}>Tidak Aktif
                </option>
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