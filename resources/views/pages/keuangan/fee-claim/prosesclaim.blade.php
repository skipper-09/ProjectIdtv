@extends('layouts.app')

@section('title', $page_name)

@push('style')
<!-- CSS Libraries -->
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

        <div class="card">
            <form action="{{ route('stb.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              {{-- <div class="card-header">
                <h4>Default Validation</h4>
              </div> --}}
              <div class="card-body">
                  
                  <div class="row">
                    <div class="form-group col-12 col-md-6">
                      <label>Nama Bank <span class="text-danger">*</span></label>
                      <input type="text" readonly value="{{$feeclaim->company->bank_name }}" class="form-control @error('bank_name') is-invalid @enderror">
                      @error('bank_name') 
                      <div class="invalid-feedback">
                          {{$message}}
                      </div>
                      @enderror
                    </div>
                  <div class="form-group col-12 col-md-6">
                      <label>Nomor Rekening <span class="text-danger">*</span></label>
                      <input type="text" name="rekening" readonly value="{{ $feeclaim->company->rekening }}" class="form-control @error('rekening') is-invalid @enderror">
                      @error('rekening')
                      <div class="invalid-feedback">
                          {{$message}}
                      </div>
                      @enderror
                  </div>
                  <div class="form-group col-12 col-md-6">
                      <label>Nomial Request <span class="text-danger">*</span></label>
                      <input type="text" name="amount" readonly value="{{ $feeclaim->company->fee_reseller }}" class="form-control @error('amount') is-invalid @enderror">
                      @error('amount')
                      <div class="invalid-feedback">
                          {{$message}}
                      </div>
                      @enderror
                  </div>
                  <div class="form-group col-12 col-md-12">
                      <label>Bukti Transfer <span class="text-danger">*</span></label>
                      <input type="file" name="internal" class="form-control @error('internal') is-invalid @enderror" value="{{old('internal')}}">
                      @error('internal')
                      <div class="invalid-feedback">
                          {{$message}}
                      </div>
                      @enderror
                  </div>
                  <div class="form-group col-12 col-md-12">
                    <label>Status <span class="text-danger">*</span></label>
                    <select class="form-control select2" id="extension" name="is_active">
                        <option value="">Pilih Status</option>
                        <option value="1">pending</option>
                        <option value="0">Aproved</option>
                        <option value="0">Rejected</option>
                    </select>
                      @error('internal')
                      <div class="invalid-feedback">
                          {{$message}}
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
<!-- JS Libraies -->
<script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
<!-- Page Specific JS File -->
@endpush