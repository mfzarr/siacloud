@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Detail Aset</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Aset</a></li>
                            <li class="breadcrumb-item active">Detail Aset</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Detail Aset</h5>
            </div>
            <div class="card-body">
                <!-- Basic Information -->
                <h6>Informasi Aset</h6>
                <table id="simpletable" class="table table-striped table-bordered">
                    <tr>
                        <th>Nama Asset</th>
                        <td>{{ $asset->nama_asset }}</td>
                    </tr>
                    <tr>
                        <th>Harga Perolehan</th>
                        <td>{{ number_format($asset->harga_perolehan, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Nilai Sisa</th>
                        <td>{{ number_format($asset->nilai_sisa, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Masa Manfaat (Tahun)</th>
                        <td>{{ $asset->masa_manfaat }}</td>
                    </tr>
                </table>

                <!-- Depreciation Summary -->
                <h6>Ringkasan Penyusutan</h6>
                <table class="table table-bordered">
                    <tr>
                        <th>Total Penyusutan</th>
                        <td>{{ number_format($total_depreciation, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Nilai Buku Saat Ini</th>
                        <td>{{ number_format($current_book_value, 2) }}</td>
                    </tr>
                </table>

                <div class="text-right">
                    <a href="{{ route('aset.index') }}" class="btn btn-secondary">Kembali</a>
                    <a href="{{ route('aset.depreciation', ['asset' => $asset->id]) }}" class="btn btn-success">Lihat Penyusutan</a>
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection