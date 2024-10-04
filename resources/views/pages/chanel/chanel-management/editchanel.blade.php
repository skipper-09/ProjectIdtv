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
                <form action="{{ route('chanel.update', ['id' => $chanel->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label>Nama Chanel</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Nama Chanel"
                                    value="{{ $chanel->name }}">
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Url</label>
                                <input type="text" name="url" class="form-control" placeholder="Url Chanel"
                                    value="{{ $chanel->url }}">
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Kategori</label>
                                <select class="form-control select2" name="categori_id">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categori as $k)
                                        <option value="{{ $k->id }}"
                                            {{ $k->id == $chanel->categori_id ? 'selected' : '' }}>{{ $k->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Extension</label>
                                <select class="form-control select2" id="extension" name="type">
                                    <option value="">Pilih Extension</option>

                                    <option value="m3u" {{ $chanel->type == 'm3u' ? 'selected' : '' }}>M3U</option>
                                    <option value="mpd" {{ $chanel->type == 'mpd' ? 'selected' : '' }}>MPD</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-12">
                                <label>User Agent</label>
                                <input type="text" name="user_agent" class="form-control"
                                    placeholder="Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0"
                                    value="{{ $chanel->user_agent }}">
                            </div>
                            <div class="form-group col-12 col-md-12">
                                <label>Logo</label>
                                <input type="file" id="logo" accept="image/*" name="logo" class="form-control"
                                    onchange="previewFile(this);">
                                <div class="row">
                                    <div class="col-12">
                                        @if (isset($chanel))
                                            <i class="text-muted small mx-3">*Kosongi jika tidak ingin mengubah logo.</i>
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <img id="img" src="#" alt="your image"
                                            class="image-preview mt-1 d-none" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Status</label>
                                <select class="form-control select2" name="is_active">
                                    <option value="">Pilih Status</option>
                                    <option value="1" {{ $chanel->is_active == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $chanel->is_active == 0 ? 'selected' : '' }}>Tidak Aktif
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6 d-none" id="security-type">
                                <label>Security Type</label>
                                <select class="form-control select2" name="security_type">
                                    <option value="">Pilih Security Type</option>
                                    <option value="widevine" {{ $chanel->security_type == 'widevine' ? 'selected' : '' }}>
                                        Widevine</option>
                                    <option value="clearkey" {{ $chanel->security_type == 'clearkey' ? 'selected' : '' }}>
                                        Clearkey</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-12 d-none" id="security">
                                <label>Security</label>
                                <input type="text" name="security" class="form-control" placeholder="Security"
                                    value="{{ $chanel->security }}">
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
        function previewFile(input) {
            var file = $("input[type=file]").get(0).files[0];


            if (file) {
                $('#img').removeClass('d-none');
                var reader = new FileReader();
                reader.onload = function() {
                    $("#img").attr("src", reader.result);
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
