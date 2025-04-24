@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Detail Penjualan</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
                            <li class="breadcrumb-item active"><a>Detail Penjualan</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Transaction Details</h5>
                <h4><strong>No Transaksi:</strong> {{ $penjualan->no_transaksi_penjualan }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Pelanggan:</strong> {{ $penjualan->pelangganRelation->nama ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tanggal Transaksi:</strong> {{ $penjualan->tgl_transaksi }}</p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <p><strong>Status:</strong>
                            {!! $penjualan->status == 'Lunas'
                            ? '<span class="badge badge-success">Lunas</span>'
                            : '<span class="badge badge-success">Lunas</span>' !!}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Dicatat oleh:</strong> {{ $penjualan->userRelation->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <h5 class="mt-4">Detail Penjualan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Kuantitas</th>
                                <th>Pegawai</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan->penjualanDetails as $detail)
                            <tr>
                                <td>{{ $detail->produkRelation->nama ?? 'N/A' }}</td>
                                <td>Rp{{ number_format($detail->harga) }}</td>
                                <td>{{ $detail->kuantitas }}</td>
                                <td>{{ $detail->pegawaiRelation->nama ?? 'N/A' }}</td>
                                <td>Rp{{ number_format($detail->total) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Discount:</strong></td>
                                <td>{{ $penjualan->discount }}%</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                <td>Rp{{ number_format($penjualan->total) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
