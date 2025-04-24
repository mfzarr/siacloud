@extends('layout')

@section('content')

    <!-- [ Main Content ] start -->
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Data from Table: {{ ucfirst($table) }}</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#!">Data Table</a></li>
                                <li class="breadcrumb-item"><a href="#!">Basic Initialization</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                
                <!-- DataTable for User Access Menu start -->
                <div class="col-sm-12 mt-4">
                    <div class="card">
                        {{-- <div class="card-header">
                            <h5>Data {{ ucfirst($table) }}</h5>
                            <div class="card-header-right">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#addmodal">Tambah Data</button>
                            </div>
                        </div> --}}
                        <div class="card-body">
                            <div class="dt-responsive table-responsive">
                                <table id="userAccessMenuTable" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>detail</th>
                                            <th>satuan</th>
                                            <th>kategori</th>
                                            @if (auth()->user()->detail === "superadmin")
                                                <th>Perusahaan</th>
                                            @endif
                                            <th>Suplier</th>
                                            <th>Pemasukan Barang</th>
                                            <th>Pengeluaran Barang</th>
                                            <th>Jumlah Saat Ini</th>
                                            <th>Keterangan</th>
                                            {{-- <th>Edit</th>
                                            <th>Delete</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barangs as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</td>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td>{{ $item->detail }}</td>
                                            <td>{{ $item->satuan }}</td>
                                            <td>{{ $item->kategori }}</td>
                                            @if (auth()->user()->detail === "superadmin")
                                                <td>{{ $item->perusahaan }}</td>
                                            @endif
                                            <td>{{ $item->supplier }}</td>
                                            <td>{{ number_format($item->stok, 0,",",".") }}</td>
                                            <td>{{ number_format($item->pengeluaran, 0,",",".") }}</td>
                                            <td>{{ number_format($item->stok, 0,",",".") }}</td>
                                            <td>{{ $item->stok <= 0 ? 'Restok' : '-' }}</td>
                                            {{-- <td>
                                                <!-- Tombol Edit -->
                                                <button type="button" class="btn btn-warning edit-button"
                                                    data-toggle="modal" data-target="#editmodal"
                                                    data-table="{{ $table }}" data-id="{{ $item->id_barang }}">
                                                    Edit
                                                </button>
                                            </td>
                                            <td>
                                                <!-- Tombol Delete -->
                                                <button type="button" class="btn btn-danger delete-button"
                                                    data-toggle="modal" data-target="#deletemodal"
                                                    data-table="{{ $table }}" data-id="{{ $item->id_barang }}">
                                                    Delete
                                                </button>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right mt-3"> <!-- Align "Kembali" button to the right -->
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DataTable for User Access Menu end -->
            </div>
        </div>
    </section>
    <!-- [ Main Content ] end -->

    {{-- @include('masterdata/' . $table . '/modal')

    @include('masterdata/' . $table . '/edit')

    @include('masterdata/' . $table . '/delete') --}}

@endsection
