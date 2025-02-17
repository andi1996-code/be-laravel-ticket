@extends('layouts.app')

@section('title', 'General Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary text-white">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Admin</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalUsers }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger text-white">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Penjualan Tiket</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalOrders }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning text-white">
                            <i class="far fa-file"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Pendapatan</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalRevenue }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info text-white">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pendapatan Hari Ini</h4>
                            </div>
                            <div class="card-body">
                                {{ $todayRevenue }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-secondary text-white">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Tiket</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalProducts }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Statistics</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <button class="btn btn-primary" id="weekBtn">Week</button>
                                    <button class="btn btn-outline-primary" id="monthBtn">Month</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart" height="182"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Best-Selling Products</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="bestSellingProductsChart" height="182"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        var salesData = {
            week: {
                labels: {!! json_encode($weekSalesData->pluck('date')) !!},
                data: {!! json_encode($weekSalesData->pluck('total')) !!}
            },
            month: {
                labels: {!! json_encode($monthSalesData->pluck('date')) !!},
                data: {!! json_encode($monthSalesData->pluck('total')) !!}
            }
        };

        var bestSellingProductsData = {
            labels: {!! json_encode($bestSellingProductsData->pluck('name')) !!},
            data: {!! json_encode($bestSellingProductsData->pluck('total')) !!}
        };

        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.week.labels,
                datasets: [{
                    label: 'Penjualan Tiket',
                    data: salesData.week.data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        document.getElementById('weekBtn').addEventListener('click', function() {
            salesChart.data.labels = salesData.week.labels;
            salesChart.data.datasets[0].data = salesData.week.data;
            salesChart.update();

            document.getElementById('weekBtn').classList.add('btn-primary');
            document.getElementById('weekBtn').classList.remove('btn-outline-primary');
            document.getElementById('monthBtn').classList.add('btn-outline-primary');
            document.getElementById('monthBtn').classList.remove('btn-primary');
        });

        document.getElementById('monthBtn').addEventListener('click', function() {
            salesChart.data.labels = salesData.month.labels;
            salesChart.data.datasets[0].data = salesData.month.data;
            salesChart.update();

            document.getElementById('monthBtn').classList.add('btn-primary');
            document.getElementById('monthBtn').classList.remove('btn-outline-primary');
            document.getElementById('weekBtn').classList.add('btn-outline-primary');
            document.getElementById('weekBtn').classList.remove('btn-primary');
        });

        var ctxPie = document.getElementById('bestSellingProductsChart').getContext('2d');
        var bestSellingProductsChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: bestSellingProductsData.labels,
                datasets: [{
                    label: 'Best-Selling Products',
                    data: bestSellingProductsData.data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' sales';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
