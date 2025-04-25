@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Presensi Karyawan ({{ $today }})</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('presensi.index') }}">Presensi</a></li>
                                <li class="breadcrumb-item active"><a>Tambah Presensi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Tambah Presensi</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($karyawans->isEmpty())
                        <div class="alert alert-info">
                            Semua karyawan telah direkam untuk presensi hari ini.
                        </div>
                    @else
                        <form id="presensiForm" method="POST" action="{{ route('presensi.store') }}">
                            @csrf
                            <input type="hidden" name="tanggal_presensi" value="{{ $today }}">

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Karyawan</th>
                                        <th>Status</th>
                                        <th>Jam Masuk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($karyawans as $karyawan)
                                        <tr>
                                            <td>{{ $karyawan->nama }}</td>
                                            <td>
                                                <select name="status[{{ $karyawan->id_karyawan }}]"
                                                    class="form-control status-select"
                                                    data-id="{{ $karyawan->id_karyawan }}" required>
                                                    <option value="hadir">Hadir</option>
                                                    <option value="izin">Izin</option>
                                                    <option value="sakit">Sakit</option>
                                                    <option value="alpha">Alpha</option>
                                                    <option value="terlambat">Terlambat</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="jam_masuk[{{ $karyawan->id_karyawan }}]"
                                                    class="form-control jam-masuk"
                                                    id="jam_masuk_{{ $karyawan->id_karyawan }}"
                                                    value="{{ now()->setTimezone('Asia/Jakarta')->format('H:i') }}"
                                                    readonly></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="text-right">
                                <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                <a href="{{ route('presensi.index') }}" class="btn btn-danger">Back</a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const statusSelects = document.querySelectorAll('.status-select');
                statusSelects.forEach(select => {
                    select.addEventListener('change', function() {
                        const karyawanId = this.getAttribute('data-id');
                        const jamMasukInput = document.getElementById('jam_masuk_' + karyawanId);
                        if (this.value === 'izin' || this.value === 'sakit' || this.value === 'alpha') {
                            jamMasukInput.value = '';
                        } else {
                            jamMasukInput.value =
                                '{{ now()->setTimezone('Asia/Jakarta')->format('H:i') }}';
                        }
                    });
                });

                document.getElementById("saveButton").addEventListener("click", function() {
                    Swal.fire({
                        title: "Create Presensi success!",
                        icon: "success",
                        draggable: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("presensiForm").submit();
                        }
                    });
                });
            });
        </script>
@endsection
