@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Edit Jabatan</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('jabatan.index') }}">Jabatan</a></li>
                                <li class="breadcrumb-item"><a>Edit Jabatan</a></li>
                            </ul>   
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Edit Jabatan</h5>
                </div>
                <div class="card-body">
                    <form id="jabatanForm" action="{{ route('jabatan.update', $jabatan->id_jabatan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama">Nama<span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $jabatan->nama) }}" required>
                            @error('nama')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="asuransi">Asuransi<span class="text-danger">*</span></label>
                            <input type="text" id="asuransi" class="form-control @error('asuransi') is-invalid @enderror"
                                value="{{ old('asuransi', number_format($jabatan->asuransi, 0, ',', '.')) }}" required>
                            <input type="hidden" name="asuransi" id="asuransi_hidden" value="{{ old('asuransi', $jabatan->asuransi) }}">
                            @error('asuransi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tarif">Tarif Tetap<span class="text-danger">*</span></label>
                            <input type="text" id="tarif" class="form-control @error('tarif') is-invalid @enderror"
                                value="{{ old('tarif', number_format($jabatan->tarif, 0, ',', '.')) }}" required>
                            <input type="hidden" name="tarif" id="tarif_hidden" value="{{ old('tarif', $jabatan->tarif) }}">
                            @error('tarif')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                            <a href="{{ route('jabatan.index') }}" class="btn btn-danger">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk memformat angka dengan ribuan
        function formatAndSetHiddenField(input, hiddenField) {
            let value = input.value.replace(/[^0-9]/g, ''); // Hapus karakter non-angka
            input.value = new Intl.NumberFormat('id-ID').format(value); // Format angka
            hiddenField.value = value; // Simpan angka murni ke hidden field
        }

        // Event listener untuk asuransi
        const asuransiInput = document.getElementById('asuransi');
        const asuransiHidden = document.getElementById('asuransi_hidden');
        asuransiInput.addEventListener('input', function () {
            formatAndSetHiddenField(asuransiInput, asuransiHidden);
        });

        // Event listener untuk tarif tetap
        const tarifTetapInput = document.getElementById('tarif');
        const tarifTetapHidden = document.getElementById('tarif_hidden');
        tarifTetapInput.addEventListener('input', function () {
            formatAndSetHiddenField(tarifTetapInput, tarifTetapHidden);
        });

        document.getElementById("saveButton").addEventListener("click", function() {
            const form = document.getElementById("jabatanForm");

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
            title: "Edit Jabatan?",
            text: "Are you sure you want to edit this Jabatan?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, edit it!'
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
