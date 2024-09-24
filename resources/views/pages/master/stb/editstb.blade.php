@extends('layouts.app')

@section('title', 'Tambah Kategori')

@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>{{$page_name}}</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{route('stb')}}">Stb</a></div>
        <div class="breadcrumb-item">{{$page_name}}</div>
      </div>
    </div>


    <div class="card">
      <form action="{{ route('stb.update',['id'=>$stb->id]) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        {{-- <div class="card-header">
          <h4>Default Validation</h4>
        </div> --}}
        <div class="card-body">
          <div class="form-group">
            <label>Nama STB <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{$stb->name}}">
            @error('name') 
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
          </div>

          <div class="row">
            <div class="form-group col-12 col-md-6">
                <label>Ram <span class="text-danger">*</span></label>
                <input type="text" name="ram" class="form-control @error('ram') is-invalid @enderror"
                value="{{$stb->ram}}">
                @error('ram')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="form-group col-12 col-md-6">
                <label>Internal <span class="text-danger">*</span></label>
                <input type="text" name="internal" class="form-control @error('internal') is-invalid @enderror" value="{{$stb->internal}}">
                @error('internal')
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
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<!-- Page Specific JS File -->
@endpush