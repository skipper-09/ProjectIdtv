@extends('components.modalcustom')

@section('modaltitle', 'Income Periode')

@section('modal-content')
    <form action="{{ route('periodeincome') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <label>Mulai Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-6">
                    <label>Sampai Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control @error('mac') is-invalid @enderror" required>
                    @error('mac')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-12">
                    <label>Type <span class="text-danger">*</span></label>
                    <select class="form-control select2 " name="type" id="typemodal">
                        <option value="">Pilih Type</option>
                        <option value="reseller">Pelanggan Reseller</option>
                        <option value="perusahaan">Pelanggan Perusahaan</option>
                    </select>
                </div>
                <div class="form-group col-12 col-md-12 d-none" id="company">
                    <label>Perusahaan </label>
                    <select class="form-control select2" name="company_id">
                        <option value="">Semua Perusahaan</option>
                        @foreach ($companies as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-12 col-md-12 d-none" id="reseller">
                    <label>Reseller </label>
                    <select class="form-control select2" name="reseller_id">
                        <option value="">Semua Reseller</option>
                        @foreach ($reseller as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class=" text-left">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#company').addClass('d-none');
            $('#reseller').addClass('d-none');
            $('#typemodal').change(function() {
                var selectedType = $(this).val();
                if (selectedType == 'perusahaan') {
                    $('#company').removeClass('d-none');
                    $('#reseller').addClass('d-none');
                } else if (selectedType == '') {
                    $('#company').addClass('d-none');
                    $('#reseller').addClass('d-none');
                } else {
                    $('#company').addClass('d-none');
                    $('#reseller').removeClass('d-none');
                }
            });
        });
    </script>
@endpush
