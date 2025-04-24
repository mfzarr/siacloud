@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Detail Pembelian</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}">Pembelian</a></li>
                            <li class="breadcrumb-item"><a>Detail Pembelian</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Transaction Details</h5>
                <h4><strong>No Transaksi:</strong> {{ $pembelian->no_transaksi_pembelian }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Supplier:</strong> {{ $pembelian->supplierRelation->nama ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tanggal Pembelian:</strong> {{ $pembelian->tanggal_pembelian }}</p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <p><strong>Total:</strong> {{ $pembelian->total }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Dibayar:</strong> {{ $pembelian->total_dibayar }}</p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <p><strong>Status:</strong> {!! $status !!}</p>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-info" onclick="openPelunasanModal()">View Pelunasan Data</button>
                    </div>
                </div>

                <h5 class="mt-4">Produk Details</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th>Dibayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembelian->pembelianDetails as $detail)
                            <tr>
                                <td>{{ $detail->produkRelation->nama ?? 'N/A' }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td>Rp{{ number_format($detail->harga) }}</td>
                                <td>Rp{{ number_format($detail->sub_total_harga) }}</td>
                                <td>Rp{{ number_format($detail->dibayar) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pelunasan Data Modal -->
<div class="modal fade" id="pelunasanModal" tabindex="-1" role="dialog" aria-labelledby="pelunasanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pelunasanModalLabel">Pelunasan Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal Pelunasan</th>
                                <th>Jumlah Pelunasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembelian->pelunasanPembelian as $pelunasan)
                            <tr>
                                <td>{{ $pelunasan->tanggal_pelunasan }}</td>
                                <td>{{ $pelunasan->total_pelunasan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openPelunasanModal() {
        $('#pelunasanModal').modal('show');
    }
</script>
@endsection
