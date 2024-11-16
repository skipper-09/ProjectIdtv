@extends('layouts.public')

@section('title', $page_name)

@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@section('bebas')
<div class="container-fluid">
    <h3 class="text-center text-uppercase p-5">Pendaftaran IDVISION</h3>
    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <form id="registrationForm" action="{{ route('customer.post') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="kode" value="{{ request()->query('kode') }}">
                    <div class="card-body">
                        <div class="row">
                            <!-- Form inputs here as in the original code -->
                            <div class="form-group col-12 col-md-6">
                                <label>Nama Customer <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Masukkan Nama Anda">
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="text" name="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Paket Pelanggan <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('paket_id') is-invalid @enderror"
                                    name="paket_id" id="pakettt">
                                    <option value="">Pilih Paket</option>
                                    @foreach ($paket as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }} - Rp.
                                        {{ number_format(isset($reseller->id) ? $s->total : $s->price) }} - {{ $s->duration ?? $s->paket->duration }} Bulan
                                    </option>
                                    @endforeach
                                </select>
                                @error('paket_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Nik<span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" name="nik"
                                    class="form-control @error('nik') is-invalid @enderror" placeholder="Nik">
                                @error('nik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>No Telepon<span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror" placeholder="No Telepon">
                                @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Username<span class="text-danger">*</span></label>
                                <input type="text" name="username" value="{{ old('username') }}"
                                    class="form-control @error('username') is-invalid @enderror" placeholder="Username">
                                @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Password <span class="text-danger">*</span></label>
                                <input type="text" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Password">
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

                            <div class="form-group col-12 col-md-6">
                                <label>Alamat <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                    cols="40" rows="20"></textarea>
                                @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                           
                        </div>
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </div>
                </form>
                <div class="note mt-3 px-4">
                    <p><strong>Note:</strong> Perhatian Setelah Melakukan Pendaftaran harap menghubungi admin</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- JS Libraries -->
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
<script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
<!-- Page Specific JS File -->
{{-- <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script> --}}

@if(session('success'))
<script>
    swal({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
</script>
@endif
{{-- <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2').select2();

            // Handle form validation and Midtrans Snap
            var form = document.getElementById('registrationForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting immediately

                // Check if the form is valid
                if (form.checkValidity()) {
                    // Trigger Midtrans Snap payment only if the form is valid
                    snap.pay('{{ $snap }}', {
                        onSuccess: function(result) {
                            form.submit(); // Submit the form after successful payment
                        },
                        onPending: function(result) {
                            form.submit(); // Submit the form even if payment is pending
                        },
                        onError: function(result) {
                            alert('Payment failed. Please try again.');
                        },
                        onClose: function() {
                            alert('Payment was not completed.');
                        }
                    });
                } else {
                    // If form is invalid, show a validation message
                    alert('Please fill in all required fields.');
                }
            });
        });
</script> --}}
@endpush