@extends('layouts.frontend')
@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Dashboard</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Dashboard</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        
        <!-- [ Filter Section ] start -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Filter Data</h5>
                    </div>
                    <div class="card-body">
                        <form id="filter-form" method="GET" action="{{ route('dashboard') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipe Filter</label>
                                        <select class="form-control" id="filterType" name="filterType" onchange="toggleFilterType()">
                                            <option value="month" {{ isset($filterType) && $filterType == 'month' ? 'selected' : '' }}>Bulan</option>
                                            <option value="range" {{ isset($filterType) && $filterType == 'range' ? 'selected' : '' }}>Rentang Tanggal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8" id="monthFilterContainer" style="{{ !isset($filterType) || $filterType == 'month' ? '' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label>Pilih Bulan</label>
                                        <input type="month" class="form-control" id="month" name="month" 
                                            value="{{ isset($currentYear) && isset($currentMonth) ? $currentYear . '-' . str_pad($currentMonth, 2, '0', STR_PAD_LEFT) : now()->format('Y-m') }}" 
                                            onchange="document.getElementById('filter-form').submit();">
                                    </div>
                                </div>
                                <div class="col-md-8" id="rangeFilterContainer" style="{{ isset($filterType) && $filterType == 'range' ? '' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label>Rentang Tanggal</label>
                                        <input type="text" class="form-control" id="dateFilter" name="dateFilter" 
                                            value="{{ isset($dateFilter) ? $dateFilter : '' }}" 
                                            placeholder="Pilih rentang tanggal">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Filter Section ] end -->
        
        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card support-bar overflow-hidden">
                    <div class="card-body pb-0">
                        <h1 class="lg-6">Ringkasan Transaksi</h1>
                        <div class="row">
                            <div class="col-md-12 col-xl-4">
                                <div class="card bg-c-green order-card">
                                    <div class="card-body">
                                        <h6 class="text-white">Total Penjualan</h6>
                                        <h2 class="text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
                                        <p class="m-b-0">Periode Terpilih</p>
                                        <i class="card-icon fas fa-money-check-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="card bg-c-yellow order-card">
                                    <div class="card-body">
                                        <h6 class="text-white">Pesanan Diterima</h6>
                                        <h2 class="text-white">{{ $totalKuantitas }}</h2>
                                        <p class="m-b-0">Total Kuantitas Periode Terpilih</p>
                                        <i class="card-icon fas fa-shopping-cart"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="card bg-c-blue order-card">
                                    <div class="card-body">
                                        <h6 class="text-white">Total Pendapatan</h6>
                                        <h2 class="text-white">Rp {{ number_format($totalSales, 0, ',', '.') }}</h2>
                                        <p class="m-b-0">Total Penjualan - HPP Periode Terpilih</p>
                                        <i class="card-icon fas fa-gift"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chart Section -->
        <div class="row">
            <!-- Pie Chart -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Top 5 Produk Terjual</h5>
                    </div>
                    <div class="card-body">
                        <div id="pie-chart">
                            {!! $salesChart->container() !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Line Chart -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Tren Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <div id="line-chart">
                            {!! $lineChart->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/daterangepicker.js') }}"></script>
{!! $salesChart->script() !!}
{!! $lineChart->script() !!}

<script>
    function toggleFilterType() {
        const filterType = document.getElementById('filterType').value;
        const monthContainer = document.getElementById('monthFilterContainer');
        const rangeContainer = document.getElementById('rangeFilterContainer');
        
        if (filterType === 'month') {
            monthContainer.style.display = '';
            rangeContainer.style.display = 'none';
        } else {
            monthContainer.style.display = 'none';
            rangeContainer.style.display = '';
        }
    }
    
    $(document).ready(function() {
        // Initialize daterangepicker
        $('#dateFilter').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            },
            ranges: {
               'Hari Ini': [moment(), moment()],
               'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
               '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
               'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
               'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });
        
        // Handle apply event
        $('#dateFilter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            $('#filter-form').submit();
        });
        
        // Handle cancel event
        $('#dateFilter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/daterangepicker.css') }}">
<style>
    .card-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 30px;
        opacity: 0.3;
    }
    
    .order-card {
        position: relative;
        overflow: hidden;
    }
</style>
@endpush