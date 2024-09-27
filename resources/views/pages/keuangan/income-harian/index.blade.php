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
                <div class="">
                    <h1>{{ $page_name }}</h1>
                    <p>Tanggal {{ now()->format('d-m-Y') }}</p>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">{{ $page_name }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success ">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>FROFIT (IDR)</h4>
                                </div>
                                <div class="card-body">
                                    {{ number_format($income) }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- <h2 class="section-title">This is Example Page</h2>
            <p class="section-lead">This page is just an example for you to create your own page.</p> --}}
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table-striped table" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>Nik</th>
                                                    <th>Nama</th>
                                                    <th>Paket</th>
                                                    <th>Perpanjang</th>
                                                    <th>Deadline</th>
                                                    <th>Status</th>
                                                    <th>Tanggal Bayar</th>
                                                    <th>Owner</th>
                                                    @canany(['update-owner', 'delete-owner'])
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
                ajax: '{{ route('dailyincome.getdata') }}',
                columns: [{
                        name: 'nik',
                        data: 'nik',
                    },
                    {
                        name: 'customer',
                        data: 'customer',
                    },
                    {
                        name: 'paket',
                        data: 'paket',
                    },
                    {
                        name: 'start_date',
                        data: 'start_date',
                    },
                    {
                        name: 'end_date',
                        data: 'end_date',
                    },
                    {
                        name: 'status',
                        data: 'status',
                    },
                    {
                        name: 'created_at',
                        data: 'created_at',
                    },
                    {
                        name: 'owner',
                        data: 'owner',
                    },
                    @canany(['update-owner', 'delete-owner'])
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
