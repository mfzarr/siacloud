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
                                <h5 class="m-b-10">Edit Pelanggan</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
                                <li class="breadcrumb-item"><a>Edit Pelanggan</a></li>
                            </ul> 
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <form id="pelangganForm" action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="nama">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                                        value="{{ $pelanggan->nama }}" required>
                                    @error('nama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                        value="{{ $pelanggan->email }}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="no_telp">No Telp <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp"
                                        value="{{ $pelanggan->no_telp }}" required>
                                    @error('no_telp')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="Pria"
                                            {{ $pelanggan->jenis_kelamin == 'Pria' ? 'selected' : '' }}>Pria
                                        </option>
                                        <option value="Wanita"
                                            {{ $pelanggan->jenis_kelamin == 'Wanita' ? 'selected' : '' }}>Wanita
                                        </option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="tgl_daftar">Tanggal Daftar <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tgl_daftar') is-invalid @enderror" id="tgl_daftar" name="tgl_daftar"
                                        value="{{ $pelanggan->tgl_daftar }}" required>
                                    @error('tgl_daftar')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" required>{{ $pelanggan->alamat }}</textarea>
                                    @error('alamat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="Aktif" {{ $pelanggan->status == 'Aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="Tidak Aktif"
                                            {{ $pelanggan->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                    <a href="{{ route('pelanggan.index') }}" class="btn btn-danger">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi flatpickr pada input tanggal dengan konfigurasi untuk memastikan input bisa diubah
            flatpickr("#tgl_daftar", {
                dateFormat: "Y-m-d",  // Format tanggal Y-m-d
                disableMobile: false,  // Memastikan Flatpickr tidak menjadi readonly pada perangkat mobile
                allowInput: true  // Memungkinkan input manual langsung ke dalam field
            });
        });  

        document.getElementById("saveButton").addEventListener("click", function() {
            const form = document.getElementById("pelangganForm");

            // Perform client-side validation
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire({
                    title: "Validation Error",
                    text: "Please fill in all required fields.",
                    icon: "error"
                });
                return;
            }

            // If client-side validation passes, show SweetAlert
            Swal.fire({
                title: "Update Pelanggan?",
                text: "Are you sure you want to update this Pelanggan?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    form.submit();
                }
            });
        });

        // Check for success message in session and show SweetAlert
        @if (session('success'))
            Swal.fire({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                draggable: true
            });
        @endif
    </script>

@endsection
