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
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pelanggan</h4>
                            </div>
                            <p class="font-weight-bold" style="font-size: 16px; color:black">
                                {{ $customer }}</p>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-building"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>PELANGGAN AKTIF</h4>
                            </div>
                            <p class="font-weight-bold" style="font-size: 16px; color:black">
                                {{ $cusactive }}</p>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-tv"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>PELANGGAN TIDAK AKTIF</h4>
                            </div>
                            <p class="font-weight-bold" style="font-size: 16px; color:black">
                                {{ $cusinactive }}</p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="section-body">
                {{-- <h2 class="section-title">This is Example Page</h2>
            <p class="section-lead">This page is just an example for you to create your own page.</p> --}}
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                @can('create-customer')
                                    <div class="card-header">
                                        <a href="{{ route('customer.add') }}" class="btn btn-primary">Tambah
                                            {{ $page_name }}</a>
                                    </div>
                                @endcan
                                @if (!Auth::user()->hasRole('Reseller'))
                                    <div class="card-header row">
                                        <div class="form-group col-12 col-md-6">
                                            <label>Filter Perusahaan <span class="text-danger">*</span></label>
                                            <select class="form-control select2 filter" id="FilterCompany"
                                                name="company_id">
                                                <option value="">Filter Perusahaan</option>
                                                @foreach ($company as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label>Filter Reseller <span class="text-danger">*</span></label>
                                            <select class="form-control select2 filter" id="FilterReseller"
                                                name="reseller_id">
                                                <option value="">Filter Reseller</option>
                                                @foreach ($resellers as $reseller)
                                                    <option value="{{ $reseller->id }}">{{ $reseller->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table-striped table" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Mac</th>
                                                    <th>Stb</th>
                                                    <th>Area</th>
                                                    <th>Diperpanjang</th>
                                                    <th>Deadline</th>
                                                    @if (!Auth::user()->hasRole('Reseller'))
                                                        <th>Perusahaan</th>
                                                    @endif
                                                    <th>Owner</th>
                                                    <th>Status</th>
                                                    <th>
                                                        @can('renew-customer')
                                                            Renew|
                                                        @endcan Print
                                                    </th>
                                                    @canany(['read-customer', 'update-customer', 'delete-customer'])
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


    {{-- //modal call --}}
    @include('components.modal')
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


    @if (session('new_tab_url'))
        <script>
            // Buka tab baru dengan URL yang diberikan dari session
            window.open("{{ session('new_tab_url') }}", '_blank');
        </script>
    @endif
    <script>
        $(document).ready(function() {


            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,

                ajax: {
                    url: '{{ route('customer.getdata') }}',
                    type: 'GET',
                    data: function(d) {
                        d.filter = $('#FilterCompany').find(":selected").val(),
                            d.reseller = $('#FilterReseller').find(":selected").val()
                    }
                },

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: '10px',
                        class: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        width: '200px'
                    },

                    {
                        data: 'mac',
                        name: 'mac'
                    },
                    {
                        data: 'stb',
                        name: 'stb',
                        orderable: false,
                        searchable: true,
                    },

                    {
                        data: 'region',
                        name: 'region',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                    },
                    @if (!Auth::user()->hasRole('Reseller'))
                        {
                            data: 'company',
                            name: 'company',
                            orderable: false,
                            searchable: true,
                        },
                    @endif {
                        data: 'reseller',
                        name: 'reseller',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        orderable: false,
                        searchable: true,
                    },

                    {
                        data: 'renew',
                        name: 'renew',
                        orderable: false,
                        searchable: false,
                    },
                    @canany(['read-customer', 'update-customer', 'delete-customer'])
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,

                        }
                    @endcanany
                ],
            });

            // Handle company filter change
            $('#FilterCompany').on('change', function() {
                $('#FilterReseller').val('').trigger('change.select2'); // Reset reseller filter
                table.ajax.reload(null, false);
            });

            // Handle reseller filter change
            $('#FilterReseller').on('change', function() {
                $('#FilterCompany').val('').trigger('change.select2'); // Reset company filter
                table.ajax.reload(null, false);
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
