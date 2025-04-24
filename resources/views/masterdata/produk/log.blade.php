@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Kartu Stok Produk: {{ $produk->nama }}</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('produk.kartustok') }}">Kartu Stok</a></li>
                                <li class="breadcrumb-item"><a href="#">{{ $produk->nama }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Kartu Stok Produk {{ $produk->nama }}</h5>
                            <h5 colspan="4" class="text-center">STOK AWAL: {{ $produk->stok_awal }}
                                ({{ $produk->created_at->format('d F Y') }})</h5>

                            {{-- <form method="GET" action="{{ route('produk.log', $produk->id_produk) }}" class="mb-4"
                                id="filterForm">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <label for="month">Pilih Bulan:</label>
                                        <input type="month" name="month" id="month" class="form-control"
                                            value="{{ $selectedMonth }}">
                                    </div>
                                </div>
                            </form> --}}

                            <div class="dt-responsive table-responsive">
                                <table id="excel-bg" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Tipe</th>
                                            <th>Jumlah</th>
                                            <th>Stok Sebelum</th>
                                            <th>Stok Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perubahan_stok as $item)
                                            <tr>
                                                <td>{{ $item['tanggal'] }}</td>
                                                <td>{{ $item['tipe'] }}</td>
                                                <td
                                                    class="{{ $item['tipe'] == 'Penjualan' ? 'text-danger' : 'text-success' }}">
                                                    {{ $item['tipe'] == 'Penjualan' ? '-' : '+' }}{{ abs($item['jumlah']) }}
                                                </td>
                                                <td class="text-primary">{{ $item['stok_sebelum'] }}</td>
                                                <td><strong>{{ $item['stok_akhir'] }}</strong></td>
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

    <script>
        document.getElementById('month').addEventListener('change', function() {
            fetch(`{{ route('produk.log', $produk->id_produk) }}?month=${this.value}`)
                .then(response => response.text())
                .then(html => {
                    const newTable = new DOMParser().parseFromString(html, 'text/html').querySelector('.dt-responsive').innerHTML;
                    document.querySelector('.dt-responsive').innerHTML = newTable;
                });
        });
    </script>
@endsection
