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
                <div class="breadcrumb-item active"><a href="{{ route('company') }}">Perusahaan</a></div>
                <div class="breadcrumb-item">{{ $page_name }}</div>
            </div>
        </div>


        <div class="card">
            <form action="{{ route('versioncontrol.update',['id'=>$version->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label>Versi<span class="text-danger">*</span></label>
                            <input type="text" name="version"
                                class="form-control @error('version') is-invalid @enderror" value="{{ $version->version }}">
                            @error('version')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Url Aplikasi<span class="text-danger">*</span></label>
                            <input type="text" name="apk_url"
                                class="form-control @error('apk_url') is-invalid @enderror" value="{{$version->apk_url}}">
                            @error('apk_url')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12">
                            <label>Catatan Release<span class="text-danger">*</span></label>
                            <textarea name="release_note" id="" cols="30"
                                class="form-control @error('release_note') is-invalid @enderror" rows="10">{{ $version->release_note }}</textarea>
                            @error('release_note')
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
    </section>
</div>
@endsection

@push('scripts')
<!-- JS Libraies -->
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
<!-- Page Specific JS File -->

@endpush