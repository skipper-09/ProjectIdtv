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
      </div>
    </div>

    <div class="section-body">
      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              @can('delete-log')
              <div class="card-header">
                <a href="{{ route('user.add') }}" class="btn btn-primary">Delete
                  {{ $page_name }}</a>
              </div>
              @endcan
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table-striped table" id="dataTable">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
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

            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('log.getdata') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: '10px',
                        class:'text-center'
                    },
                    {
                        data: 'causer',
                        name: 'causer',
                        orderable: false,
                    },
                    {
                        data: 'description',
                        name: 'description',
                        orderable: false,
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: false,
                    },
                ]
            });

        });
</script>
@endpush