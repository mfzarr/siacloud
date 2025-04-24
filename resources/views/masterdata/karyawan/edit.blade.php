@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Edit Pegawai</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}">Pegawai</a></li>
                                <li class="breadcrumb-item"><a>Edit Pegawai</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Pegawai</h5>
                        </div>
                        <div class="card-body">
                            <form id="karyawanForm" action="{{ route('pegawai.update', $karyawan->id_karyawan) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="nama">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                                        value="{{ $karyawan->nama }}" required>
                                    @error('nama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="id_user">User</span></label>
                                    <select class="form-control @error('id_user') is-invalid @enderror" id="id_user" name="id_user">
                                        <option value="">Belum Terdaftar</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ $karyawan->id_user == $user->id ? 'selected' : '' }}>
                                                {{ $user->id }} - {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_user')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                        value="{{ $karyawan->email }}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nik">NIK <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik"
                                        value="{{ $karyawan->nik }}" required>
                                    @error('nik')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="no_telp">No Telp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp"
                                        value="{{ $karyawan->no_telp }}" required>
                                    @error('no_telp')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="Pria" {{ $karyawan->jenis_kelamin == 'Pria' ? 'selected' : '' }}>
                                            Pria</option>
                                        <option value="Wanita"
                                            {{ $karyawan->jenis_kelamin == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" required>{{ $karyawan->alamat }}</textarea>
                                    @error('alamat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="id_jabatan">Jabatan <span class="text-danger">*</span></label>
                                    <select class="form-control @error('id_jabatan') is-invalid @enderror" id="id_jabatan" name="id_jabatan" required>
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach($jabatans as $jabatan)
                                            <option value="{{ $jabatan->id_jabatan }}" {{ $karyawan->id_jabatan == $jabatan->id_jabatan ? 'selected' : '' }}>
                                                {{ $jabatan->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_jabatan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="aktif" {{ $karyawan->status == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="non-aktif" {{ $karyawan->status == 'non-aktif' ? 'selected' : '' }}>
                                            Non-Aktif</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                    <a href="{{ route('pegawai.index') }}" class="btn btn-danger">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Ketika dropdown user berubah
        document.getElementById('id_user').addEventListener('change', function() {
            var userId = this.value; // Ambil ID user yang dipilih

            // Jika tidak ada ID yang dipilih, kosongkan email
            if (userId === "") {
                document.getElementById('email').value = "";
                return;
            }

            // Mengambil email berdasarkan user id dari server
            fetch(`/get-user-email/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.email) {
                        document.getElementById('email').value = data
                        .email; // Isi email dengan data yang diterima
                    } else {
                        document.getElementById('email').value = ""; // Kosongkan email jika tidak ditemukan
                    }
                })
                .catch(error => {
                    console.error('Error fetching email:', error);
                });
        });

        document.getElementById("saveButton").addEventListener("click", function() {
            const form = document.getElementById("karyawanForm");

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
            title: "Update Pegawai?",
            text: "Are you sure you want to update this Pegawai?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
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
