@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">List of Kategori Produk</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('kategori-produk.index') }}">Kategori Produk</a></li>
                            </ul> 
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Kategori Produk List</h5>
                            <div class="float-right">
                                <a href="{{ route('kategori-produk.create') }}"
                                    class="btn btn-success btn-sm btn-round has-ripple"><i class="feather icon-plus"></i>Add
                                    Kategori Produk</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($kategori_barangs->isEmpty())
                                <p>No Kategori Produk found for your perusahaan.</p>
                            @else
                                <div class="table-responsive">
                                    <table id="simpletable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                {{-- <th>no</th> --}}
                                                <th>nama</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- <?php
                                            $no = 1;
                                            ?> --}}
                                            @foreach ($kategori_barangs as $Kategori)
                                                <tr>
                                                    {{-- <td>{{$no++}}</td> --}}
                                                    <td>{{ $Kategori->nama }}</td>
                                                    <td>
                                                        <a href="{{ route('kategori-produk.edit', $Kategori->id_kategori_barang) }}"
                                                            class="btn btn-info btn-sm"><i
                                                                class="feather icon-edit"></i>&nbsp;Edit</a>
                                                        <form id="delete-form-{{ $Kategori->id_kategori_barang }}"
                                                            action="{{ route('kategori-produk.destroy', $Kategori->id_kategori_barang) }}"
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
