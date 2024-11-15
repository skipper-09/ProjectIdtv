@extends('layouts.app')

@section('title', $page_name)

@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
<link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ $page_name }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('paket') }}">Paket</a></div>
                <div class="breadcrumb-item">{{ $page_name }}</div>
            </div>
        </div>


        <div class="card">
            <form action="{{ route('reseller.reqclaimstore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label>Nama Bank <span class="text-danger">*</span></label>
                            <input type="text" name="bank_name" value="{{ $reseller->bank->name }}" readonly
                                class="form-control @error('bank_name') is-invalid @enderror"
                                value="{{ old('bank_name') }}">
                            @error('bank_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Nomor Rekening <span class="text-danger">*</span></label>
                            <input type="text" name="rekening" value="{{ $reseller->rekening }}" readonly
                                class="form-control @error('rekening') is-invalid @enderror"
                                value="{{ old('rekening') }}">
                            @error('rekening')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Nama Pemilik Rekening<span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ $reseller->owner_rek }}" readonly
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Jumlah Claim <span class="text-danger">*</span></label>
                            <input id="jumlah-claim" type="number" name="amount" readonly
                                class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}">
                            @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div>
                                        <h4>Dapat di Claim</h4>
                                        <p class="text-muted">Pilih semua data untuk diclaim pendatapan anda</p>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table-striped table" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="select-all"></th>
                                                    <th>Nik</th>
                                                    <th>Nama</th>
                                                    <th>Paket</th>
                                                    <th>Perpanjang</th>
                                                    <th>Deadline</th>
                                                    <th>Status Pembayaran</th>
                                                    <th>Fee</th>
                                                    <th>Tanggal Bayar</th>
                                                    <th>Owner</th>
                                                    <th>Status Claim</th>
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
                <div class="card-footer text-left">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
</div>
</section>
</div>
@endsection

@push('scripts')
<!-- JS Libraies -->
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
<!-- Page Specific JS File -->

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
    
        var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('reseller.getdata') }}',
        columns: [
            { 
                data: 'subscription_id', 
                name: 'subscription_id',
                orderable: false,
                searchable: false,
                render: function(data, type, full, meta) {
                   
                    const fee = full.fee ? full.fee : 0; 
                    return '<input type="checkbox" class="claim-checkbox" name="subscribe_id[]" value="' + data + '" data-fee="' + fee + '">';
                }
            },
            { name: 'nik', data: 'nik' },
            { name: 'customer', data: 'customer' },
            { name: 'paket', data: 'paket' },
            { name: 'start_date', data: 'start_date' },
            { name: 'end_date', data: 'end_date' },
            { name: 'status', data: 'status' },
            { name: 'fee',   data: 'fee'},
            { name: 'created_at', data: 'created_at' },
            { name: 'owner', data: 'owner' },
            { name: 'claim', data: 'claim' },
            @canany(['update-owner', 'delete-owner'])
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                }
            @endcanany
        ],
    });

    // Select All checkbox functionality
    $('#select-all').on('click', function() {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
        calculateTotalClaim();
    });

    // Calculate total claim when individual checkboxes are clicked
    $('#dataTable tbody').on('change', 'input.claim-checkbox', function() {
        calculateTotalClaim();
    });

    // Function to calculate total claim amount
    function calculateTotalClaim() {
        let totalClaim = 0;

        
        $('input.claim-checkbox:checked').each(function() {
            const fee = parseFloat($(this).data('fee')) || 0;
            totalClaim += fee;
        });

       
        $('#jumlah-claim').val(totalClaim);
    }
    });
</script>
@endpush