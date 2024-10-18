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
                <form action="{{ route('episode.store',['movie_id'=>$movie->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label>Judul Film</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="Judul Film">
                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Nomor Episode</label>
                                <input type="number" name="episode_number" class="form-control  @error('episode_number') is-invalid @enderror">
                                    @error('episode_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Poster</label>
                                <input type="file" accept="image/*" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror"
                                    onchange="previewFile(this);">
                                    @error('cover_image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                <img id="img" src="#" alt="your image" class="image-preview mt-1 d-none" />
                            </div>
                            
                            <div class="form-group col-12 col-md-12">
                                <label>Url</label>
                                <input type="text" name="url" class="form-control">
                            </div>
                            
                            
                            <div class="form-group col-12 col-md-6">
                                <label>Status</label>
                                <select class="form-control select2" name="status">
                                    <option value="">Pilih Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
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
