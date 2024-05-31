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
            <form action="{{ route('chanel.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label>Nama Chanel</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Chanel">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Url</label>
                            <input type="text" name="url" class="form-control" placeholder="Url Chanel">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Kategori</label>
                            <select class="form-control select2" name="categori_id">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categori as $k)
                                <option value="{{ $k->id }}">{{ $k->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Extension</label>
                            <select class="form-control select2" id="extension" name="type">
                                <option value="">Pilih Extension</option>
                                <option value="m3u">M3U</option>
                                <option value="mpd">MPD</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>User Agent</label>
                            <input type="text" name="user_agent" class="form-control" placeholder="User Agent">
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Logo</label>
                            <input type="file" accept="image/*" name="logo" class="form-control"
                                onchange="previewFile(this);" required>
                            <img id="img" src="#" alt="your image" class="image-preview mt-1 d-none" />
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Status</label>
                            <select class="form-control select2" name="status">
                                <option value="">Pilih Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-6 d-none" id="security-type">
                            <label>Security Type</label>
                            <select class="form-control select2" name="security_type">
                                <option value="">Pilih Security Type</option>
                                <option value="widevine">Widevine</option>
                                <option value="clearkey">Clearkey</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-12 d-none" id="security">
                            <label>Security</label>
                            <input type="text" name="security" class="form-control" placeholder="Security">
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


<script>
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
</script>


<script>
    function previewFile(input){
        var file = $("input[type=file]").get(0).files[0];
 
        if(file){
            $('#img').removeClass('d-none');
            var reader = new FileReader();
            reader.onload = function(){
                $("#img").attr("src", reader.result);
            }
 
            reader.readAsDataURL(file);
        }
    }
</script>

@endpush