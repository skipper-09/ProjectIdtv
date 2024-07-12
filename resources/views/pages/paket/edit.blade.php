@extends('layouts.app')

@section('title', $page_name)

@push('style')
<!-- CSS Libraries -->
@endpush

@section('main')
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>{{$page_name}}</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{route('categori-chanel')}}">Kategori</a></div>
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

<!-- Page Specific JS File -->
@endpush