@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">List of Pelanggan</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
                            </ul> 
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Pelanggan List</h5>
                            <div class="float-right">
                                <a href="{{ route('pelanggan.create') }}"
                                    class="btn btn-success btn-sm btn-round has-ripple"><i class="feather icon-plus"></i>Add
                                    Pelanggan</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($pelanggans->isEmpty())
                                <p>No pelanggan found for your perusahaan.</p>
                            @else
                                <div class="table-responsive">
                                    <table id="simpletable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>No Telpon</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Tanggal Daftar</th>
                                                <th>Alamat</th>
                                                <th>Jumlah Transaksi</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pelanggans as $pelanggan)
                                                <tr>
                                                    <td>{{ $pelanggan->nama }}</td>
                                                    <td>{{ $pelanggan->email }}</td>
                                                    <td>{{ $pelanggan->no_telp }}</td>
                                                    <td>{{ $pelanggan->jenis_kelamin }}</td>
                                                    <td>{{ $pelanggan->tgl_daftar }}</td>
                                                    <td>{{ $pelanggan->alamat }}</td>
                                                    <td>{{ $pelanggan->jumlah_transaksi}}</td>
                                                    <td>
                                                        @if ($pelanggan->status === 'Aktif')
                                                            <span class="badge badge-success">Aktif</span>
                                                        @else
                                                            <span class="badge badge-danger">Tidak Aktif</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('pelanggan.edit', $pelanggan->id_pelanggan) }}"
                                                            class="btn btn-info btn-sm"><i
                                                                class="feather icon-edit"></i>&nbsp;Edit</a>
                                                        <form id="delete-form-{{ $pelanggan->id_pelanggan }}"
                                                            action="{{ route('pelanggan.destroy', $pelanggan->id_pelanggan) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="feather icon-trash-2"></i>&nbsp;Delete
                                                            </button>
                                                        </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form[id^="delete-form-"]').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus data ini?',
                        text: "Tindakan ini tidak bisa diubah!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus data ini!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
