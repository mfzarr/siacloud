@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Jadwal Penyusutan Aset</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Aset</a></li>
                            <li class="breadcrumb-item"><a>Depresiasi Aset</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Penyusutan Aset: {{ $asset->nama_asset }}</h5>
                
                <!-- Filter by Year -->
                <form action="{{ route('aset.depreciation', $asset->id_assets) }}" method="GET" class="form-inline">
                    <div class="form-group mr-2">
                        <label for="year">Filter Berdasarkan Tahun:</label>
                        <select name="year" id="year" class="form-control ml-2" style="margin-left: 10px;" onchange="this.form.submit()">
                            <option value="">All Years</option>
                            @foreach(range(date('Y'), date('Y') + $asset->masa_manfaat) as $year)
                                <option value="{{ $year }}" 
                                    {{ $selected_year == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table id="simpletable" class="table table-striped table-bordered">
                    <thead>
                        <tr>

                            <th>Biaya Penyusutan</th>
                            <th>Akumulasi Penyusutan</th>
                            <th>Nilai Buku</th>
                            <th>Bulan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($depreciation_schedule as $schedule)
                        <tr>
                            <td>Rp{{ number_format($schedule['biaya_penyusutan']) }}</td>
                            <td>Rp{{ number_format($schedule['akumulasi_penyusutan']) }}</td>
                            <td>Rp{{ number_format($schedule['nilai_buku']) }}</td>
                            <td>{{ $schedule['bulan_tahun'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-right">
                    <a href="{{ route('aset.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
