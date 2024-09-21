@extends('layouts.app')

@section('title', $page_name)

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $page_name }}</h1>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pelanggan</h4>
                            </div>
                            <div class="card-body">
                                {{ $customer->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-building"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Perusahaan</h4>
                            </div>
                            <div class="card-body">
                                {{ $company->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-tv"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Chanel</h4>
                            </div>
                            <div class="card-body">
                                {{ $chanel->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-money-bill-trend-up"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pendapatan</h4>
                            </div>
                            <div class="card-body">
                                Rp. {{ number_format($income->sum('amount')) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Customer</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <button class="btn btn-primary" id="weekBtn">Week</button>
                                    <button class="btn" id="monthBtn">Month</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="myChart" height="182"></canvas>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Recent Activities</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border">
                                {{-- @foreach ($log as $item)
                            <li class="media">
                                <img class="rounded-circle mr-3" width="50" src="{{ asset('img/avatar/avatar-1.png') }}"
                                    alt="avatar">
                                <div class="media-body">
                                    <div class="text-primary float-right">Now</div>
                                    <div class="media-title">{{$item->causer->name}}</div>
                                    <span class="text-small text-muted"> {{$item->subject->name}}</span>
                                </div>
                            </li>
                            @endforeach --}}
                                <li class="media">
                                    <img class="rounded-circle mr-3" width="50"
                                        src="{{ asset('img/avatar/avatar-1.png') }}" alt="avatar">
                                    <div class="media-body">
                                        <div class="text-primary float-right">Now</div>
                                        <div class="media-title">Farhan A Mujib</div>
                                        <span class="text-small text-muted">Cras sit amet nibh libero, in gravida nulla.
                                            Nulla vel metus scelerisque ante sollicitudin.</span>
                                    </div>
                                </li>
                                <li class="media">
                                    <img class="rounded-circle mr-3" width="50"
                                        src="{{ asset('img/avatar/avatar-2.png') }}" alt="avatar">
                                    <div class="media-body">
                                        <div class="float-right">12m</div>
                                        <div class="media-title">Ujang Maman</div>
                                        <span class="text-small text-muted">Cras sit amet nibh libero, in gravida nulla.
                                            Nulla vel metus scelerisque ante sollicitudin.</span>
                                    </div>
                                </li>
                                <li class="media">
                                    <img class="rounded-circle mr-3" width="50"
                                        src="{{ asset('img/avatar/avatar-3.png') }}" alt="avatar">
                                    <div class="media-body">
                                        <div class="float-right">17m</div>
                                        <div class="media-title">Rizal Fakhri</div>
                                        <span class="text-small text-muted">Cras sit amet nibh libero, in gravida nulla.
                                            Nulla vel metus scelerisque ante sollicitudin.</span>
                                    </div>
                                </li>
                                <li class="media">
                                    <img class="rounded-circle mr-3" width="50"
                                        src="{{ asset('img/avatar/avatar-4.png') }}" alt="avatar">
                                    <div class="media-body">
                                        <div class="float-right">21m</div>
                                        <div class="media-title">Alfa Zulkarnain</div>
                                        <span class="text-small text-muted">Cras sit amet nibh libero, in gravida nulla.
                                            Nulla vel metus scelerisque ante sollicitudin.</span>
                                    </div>
                                </li>
                            </ul>
                            <div class="pt-1 pb-1 text-center">
                                <a href="#" class="btn btn-primary btn-lg btn-round">
                                    View All
                                </a>
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
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>

    <!-- Page Specific JS File -->
    {{-- <script src="{{ asset('js/page/index-0.js') }}"></script> --}}
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

            updateChart('week');
        });
        // Fungsi untuk memperbarui chart
        function updateChart(range) {
            $.ajax({
                url: '{{ route('customer.chart') }}', // Ganti dengan URL yang benar
                method: 'GET',
                data: {
                    range: range
                },
                success: function(response) {
                    chart.data.labels = response.labels;
                    chart.data.datasets[0].data = response.data;
                    chart.update();
                }
            });
        }

        // Inisialisasi Chart.js
        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [], // Akan diperbarui oleh AJAX
                datasets: [{
                    label: 'Customer',
                    data: [], // Akan diperbarui oleh AJAX
                    borderWidth: 5,
                    borderColor: "#6777ef",
                    backgroundColor: "transparent",
                    pointBackgroundColor: "#fff",
                    pointBorderColor: "#6777ef",
                    pointRadius: 4,
                    tension: 0.1
                }]
            },
            options: {
                legend: {
                    display: false,
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            stepSize: 150,
                        },
                    }, ],
                    xAxes: [{
                        gridLines: {
                            color: "#fbfbfb",
                            lineWidth: 2,
                        },
                    }, ],
                },
            },
        });

        // Event listener untuk tombol Week/Month
        $('#weekBtn').on('click', function() {
            $(this).addClass('btn-primary');

            // Hapus kelas 'btn-primary' dari monthBtn
            $('#monthBtn').removeClass('btn-primary');
            updateChart('week');
        });

        $('#monthBtn').on('click', function() {
            $(this).addClass('btn-primary');
            $('#weekBtn').removeClass('btn-primary');
            updateChart('month');
        });
    </script>
@endpush
