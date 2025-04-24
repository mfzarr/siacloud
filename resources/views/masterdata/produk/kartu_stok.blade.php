@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">List of Kartu Stok</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('produk.kartustok') }}">Kartu stok</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>List Produk</h5>
                            <div class="row align-items-center m-l-0">
                                <div class="col-sm-6">
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($produk->isEmpty())
                                    <p>No Produk found for your perusahaan.</p>
                                @else
                                <div class="dt-responsive table-responsive">
                                    <table id="simpletable" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <td>Nama Produk</td>
                                                    <td>Kategori</td>
                                                    <td>Actions</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($produk as $produk)
                                                        <td class="align-middle">{{ $produk->nama }}</td>
                                                        <td class="align-middle">{{ $produk->kategori_barang->nama }}</td>
                                                        <td>
                                                            <a href="{{ route('produk.log', $produk->id_produk) }}"
                                                                class="btn btn-warning btn-sm">
                                                                <i class="feather icon-clock"></i>&nbsp;Lihat Kartu Stok
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
