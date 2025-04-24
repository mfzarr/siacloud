@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5>Detail Pembelian</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}">Pembelian</a></li>
                            <li class="breadcrumb-item active">Detail Pembelian</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Detail Pembelian - {{ $pembelian->no_transaksi_pembelian }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>No Transaksi</th>
                                <td>{{ $pembelian->no_transaksi_pembelian }}</td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td>{{ $pembelian->supplierRelation->nama ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Total Hutang</th>
                                <td>Rp{{ number_format($pembelian->rekap->total_hutang) }}</td>
                            </tr>
                            <tr>
                                <th>Total Dibayar</th>
                                <td>Rp{{ number_format($pembelian->rekap->total_dibayar) }}</td>
                            </tr>
                            <tr>
                                <th>Sisa Hutang</th>
                                <td>Rp{{ number_format($pembelian->rekap->sisa_hutang) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($pembelian->rekap->sisa_hutang <= 0)
                                        <span class="badge badge-success">Lunas</span>
                                    @else
                                        <span class="badge badge-warning">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Pelunasan</th>
                                <td>{{ $pembelian->rekap->pelunasanPembelian ? $pembelian->rekap->pelunasanPembelian->tanggal_pelunasan : 'N/A' }}</td>
                            </tr>                            
                            <tr>
                                <th>Tenggat Pelunasan</th>
                                <td>{{ $pembelian->rekap->tenggat_pelunasan->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pembelian</th>
                                <td>{{ $pembelian->tanggal_pembelian->format('Y-m-d') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Daftar Produk</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembelian->pembelianDetails as $detail)
                                <tr>
                                    <td>{{ $detail->produk->nama }}</td>
                                    <td>{{ $detail->qty }}</td>
                                    <td>Rp{{ number_format($detail->harga) }}</td>
                                    <td>Rp{{ number_format($detail->qty * $detail->harga) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <a href="{{ route('pembelian.index') }}" class="btn btn-primary">Kembali ke Daftar Pembelian</a>
            </div>
        </div>
    </div>
</div>
@endsection
