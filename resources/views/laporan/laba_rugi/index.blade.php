@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Laporan Laba Rugi</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('jurnal-umum.index') }}">Jurnal Umum</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Laporan Laba Rugi</h5>
                </div>
                <div class="card-body">
                    <!-- Date Filter Form -->
                    <form method="GET" action="{{ route('laba-rugi.index') }}" class="mb-4" id="filterForm">
                        <div class="form-row">
                            <div class="col-md-10">
                                <label for="bulan">Pilih Bulan:</label>
                                <input type="month" name="bulan" id="bulan" class="form-control"
                                    value="{{ $selectedMonth }}" onchange="filterLaporan()">
                            </div>
                        </div>
                    </form>


                    <!-- End of Date Filter Form -->

                    <!-- Laporan Laba Rugi -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="text-center mb-4">
                                <h4>{{ auth()->user()->perusahaan->nama ?? 'Perusahaan' }}</h4>
                                <h5>Laporan Laba Rugi</h5>
                                <p>Periode: {{ Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</p>
                            </div>

                            <!-- Content -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode Akun</th>
                                            <th>Nama Akun</th>
                                            <th class="text-right">Saldo (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Pendapatan Section -->
                                        <tr class="table-primary">
                                            <td colspan="3" class="font-weight-bold">Pendapatan</td>
                                        </tr>
                                        @foreach ($pendapatan as $item)
                                            <tr>
                                                <td>{{ $item->kode_akun }}</td>
                                                <td>{{ $item->nama_akun }}</td>
                                                <td class="text-right">{{ number_format($item->saldo, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="font-weight-bold">
                                            <td colspan="2">Total Pendapatan</td>
                                            <td class="text-right">{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                                        </tr>

                                        <!-- Biaya Section -->
                                        <tr class="table-danger">
                                            <td colspan="3" class="font-weight-bold">Biaya</td>
                                        </tr>
                                        @foreach ($biaya as $item)
                                            <tr>
                                                <td>{{ $item->kode_akun }}</td>
                                                <td>{{ $item->nama_akun }}</td>
                                                <td class="text-right">{{ number_format($item->saldo, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="font-weight-bold">
                                            <td colspan="2">Total Biaya</td>
                                            <td class="text-right">{{ number_format($totalBiaya, 0, ',', '.') }}</td>
                                        </tr>

                                        <!-- Laba/Rugi Section -->
                                        <tr class="font-weight-bold table-success">
                                            <td colspan="2">{{ $labaRugi < 0 ? 'Rugi Bersih' : 'Laba Bersih' }}</td>
                                            <td class="text-right">{{ number_format(abs($labaRugi), 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function filterLaporan() {
            const bulan = document.getElementById('bulan').value;
            fetch(`{{ route('laba-rugi.index') }}?bulan=${bulan}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.querySelector('.table-responsive').innerHTML;
                document.querySelector('.table-responsive').innerHTML = newTable;

                // Update header
                const newHeader = doc.querySelector('.text-center.mb-4').innerHTML;
                document.querySelector('.text-center.mb-4').innerHTML = newHeader;
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection

<style>
    .card-body {
        font-family: 'Tahoma', sans-serif;
    }

    .text-right {
        text-align: right !important;
    }

    .mb-2 {
        margin-bottom: 0.5rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    input[type="month"] {
        height: 38px;
    }
</style>