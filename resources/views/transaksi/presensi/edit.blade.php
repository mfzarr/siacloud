@extends('layouts.frontend')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Edit Data Kehadiran</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('presensi.index') }}">Presensi</a></li>
                                <li class="breadcrumb-item active"><a>Edit Kehadiran</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Edit Data Kehadiran</h5>
                </div>

                <div class="card-body">
                    <form id="presensiForm" method="POST" action="{{ route('presensi.update', $date) }}">

                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendance as $record)
                                    <tr>
                                        <td>{{ $record->karyawan->nama }}</td>
                                        <td>
                                            <select name="status[{{ $record->id_karyawan }}]"
                                                class="form-control status-select" data-id="{{ $record->id_karyawan }}"
                                                required>
                                                <option value="hadir" @if ($record->status == 'hadir') selected @endif>
                                                    Hadir</option>
                                                <option value="izin" @if ($record->status == 'izin') selected @endif>
                                                    Izin</option>
                                                <option value="sakit" @if ($record->status == 'sakit') selected @endif>
                                                    Sakit</option>
                                                <option value="alpha" @if ($record->status == 'alpha') selected @endif>
                                                    Alpha</option>
                                                <option value="terlambat" @if ($record->status == 'terlambat') selected @endif>
                                                    Terlambat</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="jam_masuk[{{ $record->id_karyawan }}]"
                                                class="form-control jam-masuk flatpickr-time"
                                                id="timepicker_jam_masuk_{{ $record->id_karyawan }}"
                                                value="{{ $record->jam_masuk ? \Carbon\Carbon::parse($record->jam_masuk)->format('H:i') : '' }}">
                                        </td>
                                        <td>
                                            <input type="text" name="jam_keluar[{{ $record->id_karyawan }}]"
                                                class="form-control flatpickr-time"
                                                id="timepicker_jam_keluar_{{ $record->id_karyawan }}"
                                                value="{{ $record->jam_keluar ? \Carbon\Carbon::parse($record->jam_keluar)->format('H:i') : '' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-right">
                            <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                            <a href="{{ route('presensi.index') }}" class="btn btn-danger">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi flatpickr pada input waktu dengan format 24 jam (H:i)
            const timeInputs = document.querySelectorAll('.flatpickr-time');
            timeInputs.forEach(input => {
                flatpickr(input, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i", // Format waktu 24 jam (jam:menit)
                    time_24hr: true, // Menonaktifkan AM/PM dan menggunakan format 24 jam
                    disableMobile: false // Pastikan Flatpickr bekerja dengan benar di perangkat mobile
                });
            });

            const statusSelects = document.querySelectorAll('.status-select');
            statusSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const id = this.getAttribute('data-id');
                    const jamMasukInput = document.getElementById(`jam_masuk_${id}`);
                    const jamKeluarInput = document.getElementById(`jam_keluar_${id}`);

                    // Jika status yang dipilih adalah 'izin'
                    if (this.value === 'izin') {
                        // Kosongkan dan nonaktifkan input jam masuk dan jam keluar
                        if (jamMasukInput) {
                            jamMasukInput.value = ''; // Hapus nilai jam masuk
                            jamMasukInput.disabled = true; // Nonaktifkan input jam masuk
                        }
                        if (jamKeluarInput) {
                            jamKeluarInput.value = ''; // Hapus nilai jam keluar
                            jamKeluarInput.disabled = true; // Nonaktifkan input jam keluar
                        }
                    } else if (this.value === 'sakit' || this.value === 'alpha') {
                        // Kosongkan dan nonaktifkan input jam masuk dan jam keluar untuk status 'sakit' dan 'alpha'
                        if (jamMasukInput) {
                            jamMasukInput.value = ''; // Hapus nilai jam masuk
                            jamMasukInput.disabled = true; // Nonaktifkan input jam masuk
                        }
                        if (jamKeluarInput) {
                            jamKeluarInput.value = ''; // Hapus nilai jam keluar
                            jamKeluarInput.disabled = true; // Nonaktifkan input jam keluar
                        }
                    } else {
                        // Aktifkan input jam masuk dan jam keluar untuk status lainnya
                        if (jamMasukInput) {
                            jamMasukInput.disabled = false; // Aktifkan input jam masuk
                        }
                        if (jamKeluarInput) {
                            jamKeluarInput.disabled = false; // Aktifkan input jam keluar
                        }
                    }
                });

                // Trigger change event on page load to handle pre-selected values
                select.dispatchEvent(new Event('change'));
            });
        });

        document.getElementById("saveButton").addEventListener("click", function() {
            Swal.fire({
                title: "Edit Presensi success!",
                icon: "success",
                draggable: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("presensiForm").submit();
                }
            });
        });
    </script>
@endsection
