@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">List of Stok Produk</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Stok Produk</a></li>
                                <li class="breadcrumb-item"><a>Stok Produk</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>List Stok Produk</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="simpletable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Bulan</th>
                                            <th>Produk</th>
                                            <th>Stok Awal</th>
                                            <th>Stok Masuk</th>
                                            <th>Stok Keluar</th>
                                            <th>Stok Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produk->stok_produk as $Stok)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($Stok->bulan)->format('M-Y') }}</td>
                                                <td>{{ $Stok->produkRelation->nama ?? 'N/A' }}</td>
                                                <td>{{ $Stok->stok_awal }}</td>
                                                <td>{{ $Stok->stok_masuk }}</td>
                                                <td>{{ $Stok->stok_keluar }}</td>
                                                <td>{{ $produk->stok}}</td>
                                            </tr>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
