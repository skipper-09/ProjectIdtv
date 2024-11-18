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
                    <div class="breadcrumb-item active"><a href="{{ route('role') }}">Role</a></div>
                    <div class="breadcrumb-item">{{ $page_name }}</div>
                </div>
            </div>


            <div class="card">
                <form action="{{ route('role.update', ['id' => $role->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label>Nama<span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ $role->name }}"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Nama"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-12">
                                <div class="mb-3">
                                    <div class="d-flex align-content-start justify-content-start mb-2">
                                        <button id="select-all-btn" type="button" class="btn btn-sm btn-primary mr-2"
                                            onclick="toggleSelectAll()">Select
                                            All</button>
                                    </div>
                                    <label for="permissions" class="form-label">Pilih Hak Akses</label>
                                    <div class="row mx-4">
                                        @foreach ($permission as $permission)
                                            <div class="col-4 col-md-4">
                                                <div class="checkbox">
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $permission->name }}"
                                                        class="permission-checkbox form-check-input"
                                                        {{ isset($role) && $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ $permission->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script>
        let allSelected = false;


        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.permission-checkbox'); // Select all permission checkboxes
            allSelected = !allSelected; // Toggle the allSelected variable

            checkboxes.forEach((checkbox) => {
                checkbox.checked = allSelected; // Set the checked status based on allSelected
            });


            const selectAllBtn = document.getElementById('select-all-btn');
            selectAllBtn.textContent = allSelected ? 'Unselect All' : 'Select All';
        }

        // cek select on page load
        function checkIfAllSelected() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox
                .checked); // Check if all checkboxes are checked


            allSelected = allChecked;
            const selectAllBtn = document.getElementById('select-all-btn');
            selectAllBtn.textContent = allChecked ? 'Unselect All' : 'Select All';
        }


        document.addEventListener('DOMContentLoaded', function() {
            checkIfAllSelected();
        });
    </script>
@endpush
