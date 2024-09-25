@extends('layouts.app')

@section('title', $page_name)

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="">
                    <h1>{{ $page_name }} {{ date('Y') }}</h1>

                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">{{ $page_name }} {{ date('Y') }}</div>
                </div>
            </div>


            <div class="section-body">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                {{-- <div class="card-header">
                                    <a href="{{ route('dailyincome.export.excel') }}" class="btn btn-primary">Export
                                        {{ $page_name }}</a>
                                </div> --}}
                                <div class="card-body">
                                    <canvas id="myChart" height="182"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    @push('scripts')
        <!-- JS Libraies -->
        <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
        <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
        <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
        <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
        <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
        <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
        <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
        <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>


        <script>
            $(document).ready(function() {

                var ctx = document.getElementById('myChart').getContext('2d');
                var data = @json($data);
                const months = [];
                for (let i = 0; i < 12; i++) {
                    const month = new Date(0, i).toLocaleString('default', {
                        month: 'long'
                    });
                    months.push(month);
                }

                var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Pendapatan',
                            data: Object.values(data),
                            borderWidth: 3,
                            borderColor: "#6777ef",
                            backgroundColor: "#6777ef",
                            pointBackgroundColor: "#fff",
                            pointBorderColor: "#6777ef",
                            pointRadius: 4,
                            tension: 0.1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: 50
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
