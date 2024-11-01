@extends('layouts.app')

@section('title', $page_name)

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    <style>
        #dataTable {
            width: 100vw;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $page_name }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('customer') }}">Customer</a></div>
                    <div class="breadcrumb-item">{{ $page_name }}</div>
                </div>
            </div>


            <div class="row">

                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <form action="{{ route('customer.update', ['id' => $customer->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-6 col-md-6">
                                        <label>Nama Client <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ $customer->name }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Nama Customer">
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6 col-md-6">
                                        <label>Mac <span class="text-danger">*</span></label>
                                        <input type="text" name="mac" value="{{ $customer->mac }}"
                                            class="form-control @error('mac') is-invalid @enderror" placeholder="Mac STB">
                                        @error('mac')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Alamat <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control" placeholder="Alamat"
                                            value="{{ $customer->address }}">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Nik<span class="text-danger">*</span></label>
                                        <input type="text" name="nik" value="{{ $customer->nik }}"
                                            class="form-control @error('nik') is-invalid @enderror" placeholder="Nik">
                                        @error('nik')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>No Telepon<span class="text-danger">*</span></label>
                                        <input type="number" value="{{ $customer->phone }}" name="phone"
                                            class="form-control" placeholder="No Telepon">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Username<span class="text-danger">*</span></label>
                                        <input type="text" name="username" value="{{ $customer->username }}"
                                            class="form-control @error('username') is-invalid @enderror"
                                            placeholder="Username">
                                        @error('username')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input type="text" name="password" value="{{ $customer->showpassword }}"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Password">
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Konfirmasi Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation"
                                            value="{{ $customer->showpassword }}"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            placeholder="Konfirmasi Password">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-12">
                                        <label>Paket Pelanggan <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="paket_id">
                                            <option value="">Pilih Paket</option>
                                            @foreach ($paket as $s)
                                                <option value="{{ $s->id }}"
                                                    {{ $s->id == $latestsubcribe->packet_id ? ' selected' : '' }}>
                                                    {{ $s->name }} - Rp.
                                                    {{ number_format($s->price) }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Type STB <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="stb_id">
                                            <option value="">Pilih Type STB</option>
                                            @foreach ($stb as $s)
                                                <option value="{{ $s->id }}"
                                                    {{ $s->id == $customer->stb_id ? ' selected' : '' }}>
                                                    {{ $s->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Area <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="region_id">
                                            <option value="">Pilih Area</option>
                                            @foreach ($region as $s)
                                                <option value="{{ $s->id }}"
                                                    {{ $s->id == $customer->region_id ? ' selected' : '' }}>
                                                    {{ $s->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-md-12">
                                        <label>Perusahaan <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="company_id">
                                            <option value="">Pilih Perusahaan</option>
                                            @foreach ($company as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == $customer->company_id ? ' selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-md-12">
                                        <label>Customer Sevice <span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('user_id') is-invalid @enderror" name="user_id" >
                                            <option value="">Pilih Customer Sevice</option>
                                            @foreach ($user as $item)
                                            <option value="{{ $item->id }}"   {{ $item->id == $customer->user_id ? ' selected' : '' }}>
                                                {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Diperpanjang <span class="text-danger">*</span></label>
                                        <input type="date" name="start_date"
                                            value="{{ $latestsubcribe->start_date }}"
                                            class="form-control @error('start_date') is-invalid @enderror">
                                        @error('start_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Jatuh Tempo <span class="text-danger">*</span></label>
                                        <input type="date" name="end_date" value="{{ $latestsubcribe->end_date }}"
                                            class="form-control @error('end_date') is-invalid @enderror">
                                        @error('end_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="extension" name="is_active">
                                            <option value="">Pilih Status</option>
                                            <option value="1" {{ $customer->is_active == 1 ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="0" {{ $customer->is_active == 0 ? 'selected' : '' }}>Tidak
                                                Aktif
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-left">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-body w-100">
                            <div class="table-responsive">
                                <table class="table-striped table" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Invoice</th>
                                            <th>Nominal</th>
                                            <th>Paket</th>
                                            <th>Perpanjang</th>
                                            <th>Deadline</th>
                                            {{-- <th>Area</th> --}}
                                            <th>Perusahaan</th>
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
        </section>
    </div>

    @include('components.modal')
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <!-- Page Specific JS File -->



    {{-- custom js --}}
    <script src="{{ asset('js/custom.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('keuangan.getdata') }}',
                    data: function(data) {
                        data.id = @json($customer->id);
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
                        data: 'invoices',
                        name: 'invoices',
                    },
                    {
                        data: 'nominal',
                        name: 'nominal',
                    },
                    {
                        data: 'paket',
                        name: 'paket',
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
                    },



                    @canany(['read-customer', 'update-customer', 'delete-customer'])
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,

                        }
                    @endcanany
                ]
            });

            @if(Session::has('message'))
                iziToast.success({
                    title: `{{ Session::get('status') }}`,
                    message: `{{ Session::get('message') }}`,
                    position: 'topRight'
                });
            @endif
        });
    </script>

    {{-- <script>
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
</script> --}}
@endpush
