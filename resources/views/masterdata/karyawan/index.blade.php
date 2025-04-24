@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">List of Pegawai</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}">Pegawai</a></li>
                        </ul>   
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Pegawai List</h5>
                        <div class="float-right">
                            <a href="{{ route('pegawai.create') }}" class="btn btn-success btn-sm btn-round has-ripple"><i class="feather icon-plus"></i>Add Pegawai</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($karyawans->isEmpty())
                            <p>No Karyawan found for your perusahaan.</p>
                        @else
                            <div class="table-responsive">
                                <table id="simpletable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>NIK</th>
                                            <th>No Telp</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Alamat</th>
                                            <th>Gaji Pokok</th>
                                            <th>Akun Sistem</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($karyawans as $karyawan)
                                            <tr>
                                                <td>{{ $karyawan->nama }}</td>
                                                <td>{{ $karyawan->email }}</td>
                                                <td>{{ $karyawan->nik }}</td>
                                                <td>{{ $karyawan->no_telp }}</td>
                                                <td>{{ $karyawan->jenis_kelamin }}</td>
                                                <td>{{ $karyawan->alamat }}</td>
                                                <td>Rp{{ number_format($karyawan->tarif, 0, ',', '.') }}</td>
                                                <td>
                                                    @if($karyawan->id_user)
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    @else
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('pegawai.edit', $karyawan->id_karyawan) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="feather icon-edit"></i>&nbsp;Edit
                                                    </a>
                                                    <form id="delete-form-{{ $karyawan->id_karyawan }}"
                                                        action="{{ route('pegawai.destroy', $karyawan->id_karyawan) }}"
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
