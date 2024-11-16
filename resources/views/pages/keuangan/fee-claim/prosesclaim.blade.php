@extends('layouts.app')

@section('title', $page_name)

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
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

            <!-- Customer Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Customer Information</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table-striped table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Area</th>
                                    <th>Alamat</th>
                                    <th>Paket</th>
                                    <th>Harga</th>
                                    <th>Diperpanjang</th>
                                    <th>Deadline</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Original Fee Claim Form -->
            <div class="card">
                <form action="{{ route('feeclaim.aprove', ['id' => $feeclaim->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label>Nama Bank <span class="text-danger">*</span></label>
                                <input type="text" readonly value="{{ $feeclaim->reseller->bank->name }}"
                                    class="form-control @error('bank_name') is-invalid @enderror">
                                @error('bank_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Nomor Rekening <span class="text-danger">*</span></label>
                                <input type="text" name="rekening" readonly value="{{ $feeclaim->reseller->rekening }}"
                                    class="form-control @error('rekening') is-invalid @enderror">
                                @error('rekening')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Nama Pemilik Rekening<span class="text-danger">*</span></label>
                                <input type="text" name="owner_name" readonly
                                    value="{{ $feeclaim->reseller->owner_rek }}"
                                    class="form-control @error('owner_name') is-invalid @enderror">
                                @error('owner_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Nomial Request <span class="text-danger">*</span></label>
                                <input type="text" name="amount" readonly
                                    value="{{ number_format($feeclaim->amount) }}"
                                    class="form-control @error('amount') is-invalid @enderror">
                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-12">
                                <label>Bukti Transfer</label>
                                <input type="file" accept="image/*" name="buktitf"
                                    class="form-control @error('buktitf') is-invalid @enderror"
                                    onchange="previewFile(this);">
                                @error('buktitf')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <img id="img" src="#" alt="your image" class="image-preview mt-1 d-none" />
                            </div>
                            <div class="form-group col-12 col-md-12">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('status') is-invalid @enderror" id="extension"
                                    name="status">
                                    <option value="">Pilih Status</option>
                                    <option value="pending" {{ $feeclaim->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="aproved" {{ $feeclaim->status == 'aproved' ? 'selected' : '' }}>Aproved
                                    </option>
                                    <option value="rejected" {{ $feeclaim->status == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-left">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script>
        function previewFile(input) {
            var file = $("input[type=file]").get(0).files[0];

            if (file) {
                $('#img').removeClass('d-none');
                var reader = new FileReader();
                reader.onload = function() {
                    $("#img").attr("src", reader.result);
                }

                reader.readAsDataURL(file);
            }
        }

        $(document).ready(function() {
            var id = @json($feeclaim->id);

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('feeclaim.getdatadetail') }}',
                    type: 'GET',
                    data: {
                        id: id,
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                    },
                    {
                        data: 'region',
                        name: 'region',
                    },
                    {
                        data: 'customer_address',
                        name: 'customer_address',
                    },
                    {
                        data: 'paket',
                        name: 'paket',
                    },
                    {
                        data: 'harga',
                        name: 'harga',
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                    }
                ]
            });


            $('.filter').on('change', function() {
                let idfilterselected = $('#Filter').find(":selected").val();
                table.ajax.reload(false, null);
            })

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
