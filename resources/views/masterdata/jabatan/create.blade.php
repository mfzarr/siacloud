@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Tambah Jabatan</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('jabatan.index') }}">Jabatan</a></li>
                            <li class="breadcrumb-item">Tambah Jabatan</li>
                        </ul>    
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Add Jabatan</h5>
            </div>
            <div class="card-body">
                <form id="jabatanForm" action="{{ route('jabatan.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" required>
                        @error('nama')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="asuransi">Asuransi <span class="text-danger">*</span></label>
                        <input type="text" name="asuransi" id="asuransi" class="form-control @error('asuransi') is-invalid @enderror" required>
                        @error('asuransi')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tarif">Gaji <span class="text-danger">*</span></label>
                        <input type="text" name="tarif" id="tarif" class="form-control @error('tarif') is-invalid @enderror" required>
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
    function formatNumber(input) {
        let value = input.value.replace(/[^0-9]/g, ''); // Hapus karakter non-angka
        input.value = new Intl.NumberFormat('id-ID').format(value); // Format ribuan
    }

    // Event listener untuk input asuransi
    const asuransiInput = document.getElementById('asuransi');
    asuransiInput.addEventListener('input', function () {
        formatNumber(asuransiInput);
    });

    // Event listener untuk input tarif tetap
    const tarifTetapInput = document.getElementById('tarif');
    tarifTetapInput.addEventListener('input', function () {
        formatNumber(tarifTetapInput);
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
            title: "Create Jabatan?",
            text: "Are you sure you want to create this Jabatan?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, create it!'
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
