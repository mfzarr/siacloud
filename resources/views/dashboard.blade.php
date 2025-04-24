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
        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card support-bar overflow-hidden">
                    <div class="card-body pb-0">
                        <thead class="custom-table">
                            <tr>
                                <h1 class="lg-6">Ringkasan Transaksi</h1>
                            </tr>
                            <div class="row">
                                <div class="col-md-12 col-xl-4">
                                    <div class="card bg-c-green order-card">
                                        <div class="card-body">
                                            <h6 class="text-white">Total Penjualan</h6>
                                            <h2 class="text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
                                            <p class="m-b-0">Bulan Ini</p>
                                            <i class="card-icon fas fa-money-check-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card bg-c-yellow order-card">
                                        <div class="card-body">
                                            <h6 class="text-white">Pesanan Diterima</h6>
                                            <h2 class="text-white">{{ $totalKuantitas }}</h2>
                                            <p class="m-b-0">Total Kuantitas Bulan Ini</p>
                                            <i class="card-icon fas fa-shopping-cart"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card bg-c-blue order-card">
                                        <div class="card-body">
                                            <h6 class="text-white">Total Pendapatan</h6>
                                            <h2 class="text-white">Rp {{ number_format($totalSales, 0, ',', '.') }}</h2>
                                            <p class="m-b-0">Total Penjualan - HPP Bulan Ini</p>
                                            <i class="card-icon fas fa-gift"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </thead>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body position-relative">
                <button type="button" class="close position-absolute" style="top: 15px; right: 15px;"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="text-center">
                    <h3 class="mt-3">SIACLOUD<span class="text-primary">Perusahaan Dagang</span><sup>v1.0</sup></h3>
                </div>

                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner text-center">
                        <div class="carousel-item active" data-interval="50000">
                            <img src="{{ asset('assets/images/logosia.png') }}"
                                class="img-fluid my-4" style="max-width: 300px;" alt="images">
                            <div class="row justify-content-center">
                                <div class="col-lg-10">
                                    <div class="text-center">
                                        <h4 class="mb-3">Fitur</h4>
                                        <p class="mb-2 f-16">
                                            <i class="feather icon-check-circle mr-2 text-primary"></i>
                                            Pencatatan Transaksi Keuangan
                                        </p>
                                        <p class="mb-2 f-16">
                                            <i class="feather icon-check-circle mr-2 text-primary"></i>
                                            Penyusutan Aset
                                        </p>
                                        <p class="mb-2 f-16">
                                            <i class="feather icon-check-circle mr-2 text-primary"></i>
                                            Manajemen stok barang
                                        </p>
                                        <p class="mb-2 f-16">
                                            <i class="feather icon-check-circle mr-2 text-primary"></i>
                                            Jurnal Umum
                                        </p>
                                        <p class="mb-2 f-16">
                                            <i class="feather icon-check-circle mr-2 text-primary"></i>
                                            Laporan Keuangan
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item" data-interval="50000">
                            <img src="assets/images/model/able-admin.jpg" class="img-fluid mt-0" alt="images">
                        </div>
                    </div>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"
                    style="transform: rotate(180deg); margin-bottom: -1px;">
                    <path class="elementor-shape-fill" fill="#4680ff" opacity="0.33"
                        d="M473,67.3c-203.9,88.3-263.1-34-320.3,0C66,119.1,0,59.7,0,59.7V0h1000v59.7 c0,0-62.1,26.1-94.9,29.3c-32.8,3.3-62.8-12.3-75.8-22.1C806,49.6,745.3,8.7,694.9,4.7S492.4,59,473,67.3z"></path>
                    <path class="elementor-shape-fill" fill="#4680ff" opacity="0.66"
                        d="M734,67.3c-45.5,0-77.2-23.2-129.1-39.1c-28.6-8.7-150.3-10.1-254,39.1 s-91.7-34.4-149.2,0C115.7,118.3,0,39.8,0,39.8V0h1000v36.5c0,0-28.2-18.5-92.1-18.5C810.2,18.1,775.7,67.3,734,67.3z"></path>
                    <path class="elementor-shape-fill" fill="#4680ff"
                        d="M766.1,28.9c-200-57.5-266,65.5-395.1,19.5C242,1.8,242,5.4,184.8,20.6C128,35.8,132.3,44.9,89.9,52.5C28.6,63.7,0,0,0,0 h1000c0,0-9.9,40.9-83.6,48.1S829.6,47,766.1,28.9z"></path>
                </svg>

                <div class="modal-body text-center bg-primary py-4">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    </ol>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="ml-2">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="mr-2">Next</span>
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection