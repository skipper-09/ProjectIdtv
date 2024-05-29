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
      <form action="{{ route('customer.update',['id'=>$customer->id]) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="card-body">
          <div class="row">
            <div class="form-group col-12 col-md-6">
              <label>Nama Client<span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                placeholder="Nama Customer" value="{{$customer->name}}">
              @error('name')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="form-group col-12 col-md-6">
              <label>Mac<span class="text-danger">*</span></label>
              <input type="text" name="mac" class="form-control @error('mac') is-invalid @enderror"
                placeholder="Mac STB" value="{{$customer->mac}}">
              @error('mac')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="form-group col-12 col-md-6">
              <label>Alamat<span class="text-danger">*</span></label>
              <input type="text" name="address" class="form-control" placeholder="Alamat"
                value="{{$customer->address}}">
            </div>
            <div class="form-group col-12 col-md-6">
              <label>Ppoe<span class="text-danger">*</span></label>
              <input type="text" name="ppoe" class="form-control @error('ppoe') is-invalid @enderror" placeholder="PPOE"
                value="{{$customer->ppoe}}">
              @error('ppoe')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="form-group col-12 col-md-6">
              <label>No Telepon <span class="text-info">(optional)</span></label>
              <input type="text" name="phone" class="form-control" placeholder="No Telepon"
                value="{{$customer->phone}}">
            </div>
            <div class="form-group col-12 col-md-6">
              <label>Username<span class="text-danger">*</span></label>
              <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                placeholder="Username" value="{{$customer->username}}">
            </div>
            <div class="form-group col-12 col-md-6">
              <label>Password<span class="text-danger">*</span></label>
              <input type="text" name="password" value='{{$customer->password}}'' class="form-control @error('
                password') is-invalid @enderror" placeholder="Password">
              @error('password')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="form-group col-12 col-md-6">
              <label>Konfirmasi Password<span class="text-danger">*</span></label>
              <input type="text" name="password_confirmation" value="{{$customer->password}}"
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
                <option value="{{ $s->id }}" {{$s->id == $customer->stb_id ? ' selected' : '' }}>{{ $s->name }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-12 col-md-6">
              <label>Area<span class="text-danger">*</span></label>
              <select class="form-control select2" name="region_id">
                <option value="">Pilih Area</option>
                @foreach ($region as $s)
                <option value="{{ $s->id }}" {{$s->id == $customer->region_id ? 'selected' : ''}}>{{ $s->name }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-12 col-md-12">
              <label>Perusahaan<span class="text-danger">*</span></label>
              <select class="form-control select2" name="company_id">
                <option value="">Pilih Perusahaan</option>
                @foreach ($company as $item)
                <option value="{{ $item->id }}" {{ $item->id == $customer->company_id ? 'selected' : ''}}>{{ $item->name
                  }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-12 col-md-12">
              <label>Status<span class="text-danger">*</span></label>
              <select class="form-control select2" id="extension" name="is_active">
                <option value="">Pilih Status</option>
                <option value="1" {{ $customer->is_active == 1 ? 'selected' : '' }}>Aktif
                </option>
                <option value="0" {{ $customer->is_active == 0 ? 'selected' : '' }}>Tidak
                  Aktif
                </option>
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