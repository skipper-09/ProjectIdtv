@extends('layouts.app')

@section('title', 'Chanel')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
    href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{$page_name}}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Chanel</a></div>
                    <div class="breadcrumb-item">{{$page_name}}</div>
                    {{-- <div class="breadcrumb-item">Default Layout</div> --}}
                </div>
            </div>

            <div class="section-body">
                {{-- <h2 class="section-title">This is Example Page</h2>
                <p class="section-lead">This page is just an example for you to create your own page.</p> --}}
                <div class="section-body">
    
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <button class="btn btn-primary">Tambah Kategori</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table-striped table"
                                            id="table-1">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        #
                                                    </th>
                                                    <th>Task Name</th>
                                                    <th>Progress</th>
                                                    <th>Members</th>
                                                    <th>Due Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        1
                                                    </td>
                                                    <td>Create a mobile app</td>
                                                    <td class="align-middle">
                                                        <div class="progress"
                                                            data-height="4"
                                                            data-toggle="tooltip"
                                                            title="100%">
                                                            <div class="progress-bar bg-success"
                                                                data-width="100%"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <img alt="image"
                                                            src="{{ asset('img/avatar/avatar-5.png') }}"
                                                            class="rounded-circle"
                                                            width="35"
                                                            data-toggle="tooltip"
                                                            title="Wildan Ahdian">
                                                    </td>
                                                    <td>2018-01-20</td>
                                                    <td>
                                                        <div class="badge badge-success">Completed</div>
                                                    </td>
                                                    <td><a href="#"
                                                            class="btn btn-secondary">Detail</a></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        2
                                                    </td>
                                                    <td>Redesign homepage</td>
                                                    <td class="align-middle">
                                                        <div class="progress"
                                                            data-height="4"
                                                            data-toggle="tooltip"
                                                            title="0%">
                                                            <div class="progress-bar"
                                                                data-width="0"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <img alt="image"
                                                            src="{{ asset('img/avatar/avatar-1.png') }}"
                                                            class="rounded-circle"
                                                            width="35"
                                                            data-toggle="tooltip"
                                                            title="Nur Alpiana">
                                                        <img alt="image"
                                                            src="{{ asset('img/avatar/avatar-3.png') }}"
                                                            class="rounded-circle"
                                                            width="35"
                                                            data-toggle="tooltip"
                                                            title="Hariono Yusup">
                                                        <img alt="image"
                                                            src="{{ asset('img/avatar/avatar-4.png') }}"
                                                            class="rounded-circle"
                                                            width="35"
                                                            data-toggle="tooltip"
                                                            title="Bagus Dwi Cahya">
                                                    </td>
                                                    <td>2018-04-10</td>
                                                    <td>
                                                        <div class="badge badge-info">Todo</div>
                                                    </td>
                                                    <td><a href="#"
                                                            class="btn btn-secondary">Detail</a></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        3
                                                    </td>
                                                    <td>Backup database</td>
                                                    <td class="align-middle">
                                                        <div class="progress"
                                                            data-height="4"
                                                            data-toggle="tooltip"
                                                            title="70%">
                                                            <div class="progress-bar bg-warning"
                                                                data-width="70%"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <img alt="image"
                                                            src="{{ asset('img/avatar/avatar-1.png') }}"
                                                            class="rounded-circle"
                                                            width="35"
                                                            data-toggle="tooltip"
                                                            title="Rizal Fakhri">
                                                        <img alt="image"
                                                            src="{{ asset('img/avatar/avatar-2.png') }}"
                                                            class="rounded-circle"
                                                            width="35"
                                                            data-toggle="tooltip"
                                                            title="Hasan Basri">
                                                    </td>
                                                    <td>2018-01-29</td>
                                                    <td>
                                                        <div class="badge badge-warning">In Progress</div>
                                                    </td>
                                                    <td><a href="#"
                                                            class="btn btn-secondary">Detail</a></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        4
                                                    </td>
                                                    <td>Input data</td>
                                                    <td class="align-middle">
                                                        <div class="progress"
                                                            data-height="4"
                                                            data-toggle="tooltip"
                                                            title="100%">
                                                            <div class="progress-bar bg-success"
                                                                data-width="100%"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <img alt="image"
                                                            src="{{ asset('img/avatar/avatar-2.png') }}"
                                                            class="rounded-circle"
                                                            width="35"
                                                            data-toggle="tooltip"
                                                            title="Rizal Fakhri">
                                                        <img alt="image"
                                                            src="{{ asset('img/avatar/avatar-5.png') }}"
                                                            class="rounded-circle"
                                                            width="35"
                                                            data-toggle="tooltip"
                                                            title="Isnap Kiswandi">
                                                        <img alt="image"
                                                            src="{{ asset('img/avatar/avatar-4.png') }}"
                                                            class="rounded-circle"
                                                            width="35"
                                                            data-toggle="tooltip"
                                                            title="Yudi Nawawi">
                                                        <img alt="image"
                                                            src="{{ asset('img/avatar/avatar-1.png') }}"
                                                            class="rounded-circle"
                                                            width="35"
                                                            data-toggle="tooltip"
                                                            title="Khaerul Anwar">
                                                    </td>
                                                    <td>2018-01-16</td>
                                                    <td>
                                                        <div class="badge badge-success">Completed</div>
                                                    </td>
                                                    <td><a href="#"
                                                            class="btn btn-secondary">Detail</a></td>
                                                </tr>
                                            </tbody>
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
    {{-- <script src="{{ asset() }}"></script> --}}
    {{-- <script src="{{ asset() }}"></script> --}}
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <!-- Page Specific JS File -->
@endpush
