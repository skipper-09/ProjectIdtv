@extends('layouts.app')

@section('main')
    @extends('layouts.app')

@section('title', $page_name)

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">

    {{-- button --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css">
    <style>
        .dt-buttons {
            margin-bottom: 10px;
        }

        .dt-buttons button {
            background-color: #04AA6D;
            /* Green */
            border-radius: 5px;
            border: none;
            color: white;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .dt-buttons button:hover {
            background-color: #04AA6D !important;
            border: none !important;
            color: white;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="">
                    <h1>{{ $page_name }}</h1>
                    <p>Mulai Tanggal <strong>{{ $start_date }}</strong> S/d
                        <strong>{{ $end_date }}</strong>
                    </p>
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
                                {{-- <div class="card-header">
                                    <a href="{{ route('dailyincome.export.excel') }}" class="btn btn-primary">Export
                                        {{ $page_name }}</a>
                                </div> --}}
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

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/custom.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {

            var start = @json($start_date);
            var end = @json($end_date);

            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('periodeincome.getdata') }}',
                    data: {
                        start_date: start,
                        end_date: end
                    }
                },
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
                    @canany(['update-owner', 'delete-owner'])
                        {
                            data: 'action',
                            name: 'action',
                        }
                    @endcanany
                ],
                dom: 'Bfrtip', // This is needed for the buttons to appear
                buttons: [{
                    extend: 'csv',
                    text: 'Export as CSV',
                    exportOptions: {
                        columns: ':visible:not(:last-child)' // Export all columns except the last (actions)
                    },
                    filename: function() {
                        var d = new Date();
                        return 'Data_Income_' + d.getFullYear() + '_' + (d.getMonth() + 1) +
                            '_' + d.getDate();
                    }
                }, ]
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

@endsection
