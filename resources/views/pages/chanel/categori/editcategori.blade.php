@extends('layouts.app')

@section('title', 'Edit Kategori')

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
      <form action="{{ route('categori-chanel.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- <div class="card-header">
          <h4>Default Validation</h4>
        </div> --}}
        <div class="card-body">
          <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="name" class="form-control">
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