@extends('layouts.app')

@section('title', $page_name)

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
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
                                @can('create-movie')
                                    <div class="card-header d-flex justify-content-between align-content-between">
                                        <a href="{{ route('movie.add') }}" class="btn btn-primary">Tambah
                                            {{ $page_name }}</a>
                                            <div class="d-flex gap-3">
                                                <a href="{{ route('movie.export') }}" class="btn btn-success mr-2">Export
                                                    {{ $page_name }}</a>
                                                <a href="#" data-toggle="modal" data-type="show" data-target="#showmodalimport" class="btn btn-success">Import
                                                    {{ $page_name }}</a>
                                            </div>
                                    </div>
                                @endcan
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table-striped table" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Genre</th>
                                                    <th>Cover</th>
                                                    <th>Type</th>
                                                    <th>Deskripsi</th>
                                                    @canany(['update-movie', 'delete-movie'])
                                                        <th>Action</th>
                                                    @endcanany
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
    @include('components.importmodal',[
        'route'=> route('movie.importfile'),
    ])
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/custom.js') }}"></script>
    <!-- Page Specific JS File -->


    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('movie.getdata') }}',
                columns: [{
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'genre',
                        name: 'genre'
                    },
                    {
                        data: 'cover_image',
                        name: 'cover_image'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    @canany(['update-movie', 'delete-movie'])
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                        }
                    @endcanany
                ]
            });



            @if (Session::has('message'))
                iziToast.success({
                    title: `{{ Session::get('status') }}`,
                    message: `{{ Session::get('message') }}`,
                    position: 'topRight'
                });
            @endif

        });
    </script>
@endpush
