@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Laporan Cash Flow</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item active">Cash Flow</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Cash Flow Section (Collapsible) -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 d-inline-block">Cash Flow Harian</h5>
                <button class="btn btn-primary float-right" data-toggle="collapse" data-target="#collapseDaily" aria-expanded="false" aria-controls="collapseDaily">
                    Tampilkan
                </button>
            </div>

            <div id="collapseDaily" class="collapse">
                <div class="card-body">
                    <form method="GET" action="{{ route('cashflow') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <tr>
                            <th>Pemasukan</th>
                            <td>Rp {{ number_format($pemasukan_harian, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Pengeluaran</th>
                            <td>Rp {{ number_format($pengeluaran_harian, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Saldo</th>
                            <td>Rp {{ number_format($saldo_harian, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Monthly Cash Flow Section (Collapsible) -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0 d-inline-block">Cash Flow Bulanan</h5>
                <button class="btn btn-primary float-right" data-toggle="collapse" data-target="#collapseMonthly" aria-expanded="false" aria-controls="collapseMonthly">
                    Tampilkan
                </button>
            </div>

            <div id="collapseMonthly" class="collapse">
                <div class="card-body">
                    <form method="GET" action="{{ route('cashflow') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <select name="bulan" class="form-control" onchange="this.form.submit()">
                                    <option value="1" {{ $bulan == 1 ? 'selected' : '' }}>Januari</option>
                                    <option value="2" {{ $bulan == 2 ? 'selected' : '' }}>Februari</option>
                                    <option value="3" {{ $bulan == 3 ? 'selected' : '' }}>Maret</option>
                                    <option value="4" {{ $bulan == 4 ? 'selected' : '' }}>April</option>
                                    <option value="5" {{ $bulan == 5 ? 'selected' : '' }}>Mei</option>
                                    <option value="6" {{ $bulan == 6 ? 'selected' : '' }}>Juni</option>
                                    <option value="7" {{ $bulan == 7 ? 'selected' : '' }}>Juli</option>
                                    <option value="8" {{ $bulan == 8 ? 'selected' : '' }}>Agustus</option>
                                    <option value="9" {{ $bulan == 9 ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ $bulan == 10 ? 'selected' : '' }}>Oktober</option>
                                    <option value="11" {{ $bulan == 11 ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ $bulan == 12 ? 'selected' : '' }}>Desember</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="tahun" class="form-control" value="{{ $tahun }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <tr>
                            <th>Pemasukan</th>
                            <td>Rp {{ number_format($pemasukan_bulanan, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Pengeluaran</th>
                            <td>Rp {{ number_format($pengeluaran_bulanan, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Saldo</th>
                            <td>Rp {{ number_format($saldo_bulanan, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
