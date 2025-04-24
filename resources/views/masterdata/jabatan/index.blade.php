@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">List of Jabatan</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#!">Jabatan</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">                            <h5>Jabatan List</h5>
                            <div class="float-right">
                                <a href="{{ route('jabatan.create') }}"
                                    class="btn btn-success btn-sm btn-round has-ripple"><i class="feather icon-plus"></i>Add
                                    Jabatan</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($jabatans->isEmpty())
                                <p>No jabatans found for your perusahaan.</p>
                            @else
                                <div class="table-responsive">
                                    <table id="simpletable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Asuransi</th>
                                                <th>Gaji</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jabatans as $jabatan)
                                                <tr>
                                                    <td>{{ $jabatan->nama }}</td>
                                                    <td>Rp{{ number_format($jabatan->asuransi, 0, ',', '.') }}
                                                    </td>
                                                    <td>Rp{{ number_format($jabatan->tarif, 0, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('jabatan.edit', $jabatan->id_jabatan) }}"
                                                            class="btn btn-info btn-sm">
                                                            <i class="feather icon-edit"></i>&nbsp;Edit
                                                        </a>
                                                        <form id="delete-form-{{ $jabatan->id_jabatan }}"
                                                            action="{{ route('jabatan.destroy', $jabatan->id_jabatan) }}"
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