@extends('layouts.app')

@section('title', $page_name)

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
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
                        <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-12 col-md-6">
                                        <label>Nama Client <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Nama Customer">
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Mac <span class="text-danger">*</span></label>
                                        <input type="text" name="mac" value="{{ old('mac') }}"
                                            class="form-control @error('mac') is-invalid @enderror" placeholder="Mac STB">
                                        @error('mac')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Alamat <span class="text-danger">*</span></label>
                                        <input type="text" value="{{ old('address') }}" name="address"
                                            class="form-control @error('address') is-invalid @enderror""
                                            placeholder="Alamat">
                                        @error('address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Nik<span class="text-danger">*</span></label>
                                        <input type="number" name="nik" value="{{ old('nik') }}"
                                            class="form-control @error('nik') is-invalid @enderror" placeholder="Nik">
                                        @error('nik')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>No Telepon<span class="text-danger">*</span></label>
                                        <input type="number" value="{{ old('phone') }}" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="No Telepon">
                                        @error('phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Username<span class="text-danger">*</span></label>
                                        <input type="text" name="username" value="{{ old('username') }}"
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
                                        <input type="text" name="password"
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
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            placeholder="Konfirmasi Password">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-12">
                                        <label>Type <span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('type') is-invalid @enderror"
                                            name="type" id="type">
                                            <option value="">Pilih Type</option>
                                            <option value="reseller">Pelanggan Reseller</option>
                                            <option value="perusahaan">Pelanggan Perusahaan</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-12 d-none" id="perusahaan">
                                        <label>Perusahaan <span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('company_id') is-invalid @enderror"
                                            name="company_id" id="company">
                                            <option value="">Pilih Perusahaan</option>
                                            @foreach ($company as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-12 d-none" id="paket">
                                        <label>Paket Pelanggan <span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('paket_id') is-invalid @enderror"
                                            name="paket_id" id="package">
                                            <option value="">Pilih Paket</option>
                                            @foreach ($paket as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }} - Rp.
                                                    {{ number_format($s->price) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('paket_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-12 d-none" id="reseller">
                                        <label>Reseller <span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('reseller_id') is-invalid @enderror"
                                            name="reseller_id" id="reseller_select">
                                            <option value="">Pilih Reseller</option>
                                            @foreach ($reseller as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('reseller_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-12 d-none" id="resellerpaket">
                                        <label>Paket Reseller <span class="text-danger">*</span></label>
                                        <select
                                            class="form-control select2 @error('resellerpaket_id') is-invalid @enderror"
                                            name="resellerpaket_id" id="packagereseller">
                                            <option value="">Pilih Paket Reseller</option>
                                        </select>
                                        @error('resellerpaket_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Type STB <span class="text-danger">*</span></label>
                                        <select class="form-control @error('stb_id') is-invalid @enderror select2"
                                            name="stb_id">
                                            <option value="">Pilih Type STB</option>
                                            @foreach ($stb as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }} </option>
                                            @endforeach
                                        </select>
                                        @error('stb_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Area <span class="text-danger">*</span></label>
                                        <select class="form-control @error('region_id') is-invalid @enderror select2"
                                            name="region_id">
                                            <option value="">Pilih Area</option>
                                            @foreach ($region as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }} </option>
                                            @endforeach
                                        </select>
                                        @error('region_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-6">
                                        <label>Jatuh Tempo <span class="text-danger">*</span></label>
                                        <input type="date" name="end_date"
                                            class="form-control @error('end_date') is-invalid @enderror">
                                        @error('end_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Status</label>
                                        <select class="form-control select2" id="extension" name="is_active">
                                            <option value="">Pilih Status</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer text-left">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                        </form>
                    </div>
                </div>

            </div>

        </section>
    </div>
@endsection

@push('scripts')
    <!-- JavaScript Libraries -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>

    <script>
 @if (Session::has('message'))
                iziToast.success({
                    title: `{{ Session::get('status') }}`,
                    message: `{{ Session::get('message') }}`,
                    position: 'topRight'
                });
            @endif

        $(document).ready(function() {
            $('.select2').select2();

            $('#type').change(function() {
                var selected = $(this).val();
                if (selected == 'perusahaan') {
                    $('#perusahaan, #paket').removeClass('d-none');
                    $('#reseller, #resellerpaket').addClass('d-none');
                } else if (selected == 'reseller') {
                    $('#reseller, #resellerpaket').removeClass('d-none');
                    $('#perusahaan, #paket').addClass('d-none');
                } else {
                    $('#perusahaan, #paket, #reseller, #resellerpaket').addClass('d-none');
                }
            });

            $('#reseller_select').change(function() {
                var resellerId = $(this).val();
                if (resellerId) {
                    $.ajax({
                        url: '{{ route('customer.getPaketReseller') }}',
                        type: 'GET',
                        data: {
                            reseller_id: resellerId
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('#packagereseller').empty().append(
                                '<option value="">Pilih Paket Reseller</option>');
                            $.each(data, function(key, value) {
                                $('#packagereseller').append('<option value="' + value
                                    .id + '">' + value.name + ' - Rp. ' + value
                                    .total.toLocaleString() + '</option>');
                            });
                            $('#packagereseller').trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                            alert('Failed to fetch reseller packages. Please try again.');
                        }
                    });
                } else {
                    $('#packagereseller').empty().append('<option value="">Pilih Paket Reseller</option>');
                }
            });
        });
    </script>
@endpush
