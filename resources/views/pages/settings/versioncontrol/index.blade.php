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
                </div>
            </div>

            <div class="section-body">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                @can('create-version_control')
                                    <div class="card-header">
                                        <a href="{{ route('versioncontrol.add') }}" class="btn btn-primary">Tambah
                                            {{ $page_name }}</a>
                                    </div>
                                @endcan
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table-striped table" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Versi</th>
                                                    <th>Url Aplikasi</th>
                                                    <th>Catatan Aplikasi</th>
                                                    <th>Action</th>
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
                ajax: '{{ route('versioncontrol.getdata') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: '10px',
                        class: 'text-center'
                    },
                    {
                        data: 'version',
                        name: 'version'
                    },
                    {
                        data: 'apk_url',
                        name: 'apk_url'
                    },
                    {
                        data: 'release_note',
                        name: 'release_note'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    }

                ]
            });

        });
        @if (Session::has('message'))
                iziToast.success({
                    title: `{{ Session::get('status') }}`,
                    message: `{{ Session::get('message') }}`,
                    position: 'topRight'
                });
            @endif
    </script>
@endpush