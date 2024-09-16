@extends('layouts.app')

@section('title', $page_name)

@push('style')
    <!-- CSS Libraries -->
    <link href="https://vjs.zencdn.net/8.16.1/video-js.css" rel="stylesheet" />
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $page_name }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">{{ $page_name }}</div>
                    {{-- <div class="breadcrumb-item">Default Layout</div> --}}
                </div>
            </div>

            <div class="section-body">
                {{-- <h2 class="section-title">This is Example Page</h2>
            <p class="section-lead">This page is just an example for you to create your own page.</p> --}}
                <div class="section-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <video id="my-video" class="video-js" controls='false' autoplay='true'>
                                    <source
        src="https://live-par-2-cdn-alt.livepush.io/live/bigbuckbunnyclip/index.m3u8"
        type="application/x-mpegURL"
      />
                                    <p class="vjs-no-js">
                                        To view this video please enable JavaScript,
                                        and consider upgrading to a web browser that
                                        <a href="https://videojs.com/html5-video-support/" target="_blank">
                                            supports HTML5 video
                                        </a>
                                    </p>
                                </video>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/custom.js') }}"></script>
    <!-- Page Specific JS File -->

    {{-- vidio --}}
    <script src="https://vjs.zencdn.net/8.16.1/video.min.js"></script>
    <script>
        var player = videojs('my-video');
        player.play();
    </script>

@endpush
