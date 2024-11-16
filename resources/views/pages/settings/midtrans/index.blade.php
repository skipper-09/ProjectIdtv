@extends('layouts.app')

@section('title', $page_name)


@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
<link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
@endpush

@section('main')

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ $page_name }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">{{ $page_name }}</div>
            </div>
        </div>

        <div class="card">
            <form action="{{ route('midtrans.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label>CLIENT KEY<span class="text-danger">*</span></label>
                            <input type="text" name="client_key" class="form-control @error('client_key') is-invalid @enderror"
                                placeholder="Nama" value="{{ $midtrans->client_key }}" >
                            @error('client_key')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>SERVER KEY<span class="text-danger">*</span></label>
                            <input type="text" name="server_key" class="form-control @error('server_key') is-invalid @enderror"
                            value="{{ $midtrans->server_key }}">
                            @error('server_key')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div> 
                        <div class="form-group col-12 col-md-6">
                            <label>URL<span class="text-danger">*</span></label>
                            <input type="text" name="url" class="form-control @error('url') is-invalid @enderror"
                                value="{{ $midtrans->url }}">
                            @error('url')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div> 
                        <div class="form-group col-12 col-md-6">
                            <label>Pilih Mode <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('production') is-invalid @enderror" name="production">
                                <option value="">Pilih Mode</option>
                                <option value="0" {{ $midtrans->production == 0 ? 'selected' : '' }}>Development</option>
                                <option value="1" {{ $midtrans->production == 1 ? 'selected' : '' }}>Production</option>
                            </select>
                            @error('production')
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
<script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
<!-- Page Specific JS File -->

<script>

    @if (Session::has('message'))
    iziToast.success({
        title: `{{ Session::get('status') }}`,
        message: `{{ Session::get('message') }}`,
        position: 'topRight'
    });
    @endif
</script>

@endpush