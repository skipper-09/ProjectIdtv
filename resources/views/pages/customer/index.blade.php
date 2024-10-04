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
                            @can('create-customer')
                            <div class="card-header">
                                <a href="{{ route('customer.add') }}" class="btn btn-primary">Tambah
                                    {{ $page_name }}</a>
                            </div>
                            @endcan
                          @if (!Auth::user()->hasRole('Reseller'))
                          <div class="card-header row">
                            <div class="form-group col-6 col-md-4">
                                <label>Filter Perusahaan <span class="text-danger">*</span></label>
                                <select class="form-control select2 filter" id="Filter" name="company_id">
                                    <option value="">Filter Perusahaan</option>
                                    @foreach ($company as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}</option>
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
                                                <th>Nik</th>
                                                <th>Nama</th>
                                                <th>Mac</th>
                                                <th>Stb</th>
                                                <th>Area</th>
                                                <th>Diperpanjang</th>
                                                <th>Deadline</th>
                                                <th>Perusahaan</th>
                                                <th>Status</th>
                                                <th>Renew|Print</th>
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



<script>
    $(document).ready(function() {
        

             var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
             
                ajax: {
                   url: '{{ route('customer.getdata') }}',
                   type:'GET',
                   data: function(d){
                    d.filter = $('#Filter').find(":selected").val()
                   }
                },
               
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     orderable: false,
                    //     searchable: false,
                    //     width: '10px',
                    //     class:'text-center'
                    // },
                    {
                        data: 'nik',
                        name: 'nik',
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
                    {
                        data: 'company',
                        name: 'company',
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

            $('.filter').on('change',function(){
                let idfilterselected = $('#Filter').find(":selected").val();
                table.ajax.reload(false,null);
            })

                @if(Session::has('message'))
                iziToast.success({
                    title: `{{ Session::get('status') }}`,
                    message: `{{ Session::get('message') }}`,
                    position: 'topRight'
                });
            @endif
        });
</script>
@endpush