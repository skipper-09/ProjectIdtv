@extends('components.modalcustom')

@section('modaltitle', 'Income Periode')

@section('modal-content')
    <form action="{{ route('periodeincome') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <label>Mulai Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-6">
                    <label>Sampai Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control @error('mac') is-invalid @enderror">
                    @error('mac')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class=" text-left">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
