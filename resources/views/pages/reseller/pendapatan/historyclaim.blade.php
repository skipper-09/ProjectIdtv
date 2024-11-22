@extends('layouts.app')

@section('title', $page_name)

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $page_name }}</h1>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <a href="{{ route('customer') }}">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-landmark"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Claim</h4>
                                </div>
                                <p class="font-weight-bold" style="font-size: 16px; color:black">
                                    Rp. {{ number_format($claim) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- 
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-money-bill-trend-up"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pendapatan</h4>
                        </div>
                        <p class="font-weight-bold" style="font-size: 16px; color:black"> Rp.
                            10</p>
                    </div>
                </div>
            </div> --}}
            </div>

            <div class="col-12 mb-3">
                <div class="d-flex justify-content-center align-items-center">

                    <a href="{{ route('pendapatan.reseller') }}"><button class="btn btn-sm btn-success mr-2">Data Dapat di
                            Claim</button></a>
                    {{-- <a href="{{ route('reseller.reqclaim') }}"><button class="btn btn-sm btn-primary mr-2">Ajukan Claim</button></a> --}}

                </div>
            </div>
            {{-- card --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>History Pengajuan Claim</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Nama Bank</th>
                                        <th>Nomor Rekening</th>
                                        <th>Nama Pemilik Rekening</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status</th>
                                        <th>Reseller</th>
                                        <th>Detail</th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('components.modal')
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/custom.js') }}"></script>
    {{-- <script src="{{ asset('js/page/index-0.js') }}"></script> --}}
    <script>
        @if (Session::has('message'))
            iziToast.success({
                title: `{{ Session::get('status') }}`,
                message: `{{ Session::get('message') }}`,
                position: 'topRight'
            });
        @endif
    </script>
    <script>
        $(document).ready(function() {

            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('reseller.datahistory') }}',
                columns: [{
                        data: 'bank_name',
                        name: 'bank_name',
                    },
                    {
                        data: 'rekening',
                        name: 'rekening',
                    },

                    {
                        data: 'owner_rek',
                        name: 'owner_rek',
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },

                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'reseller',
                        name: 'reseller',
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
    </script>
@endpush
