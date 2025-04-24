@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">List of Produk/Barang</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Barang</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5>List Produk/Barang</h5>
                        <a href="{{ route('barang2.create') }}" class="btn btn-primary">Add Barang</a>
                    </div>
                    <div class="card-body">
                        @if($barangs->isEmpty())
                            <p>No barang found for your perusahaan.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Detail</th>
                                            <th>Satuan</th>
                                            <th>Harga Jual</th>
                                            <th>Harga Pokok Penjualan (HPP)</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($barangs as $barang)
                                            <tr>
                                                <td>{{ $barang->nama }}</td>
                                                <td>{{ $barang->detail }}</td>
                                                <td>{{ $barang->satuan }}</td>
                                                <td>Rp{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                                                <td>Rp{{ number_format($barang->HPP, 0, ',', '.') }}</td>
                                                <td>
                                                    <a href="{{ route('barang2.edit', $barang->id_barang) }}" class="btn btn-warning">Edit</a>
                                                    <form id="delete-form-{{ $barang->id_barang }}" action="{{ route('barang2.destroy', $barang->id_barang) }}" method="POST" style="display:inline;">
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
