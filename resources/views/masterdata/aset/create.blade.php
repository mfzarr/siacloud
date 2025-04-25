@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Tambah Aset</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Aset</a></li>
                            <li class="breadcrumb-item"><a>Tambah Aset</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Add Aset</h5>
            </div>
            <div class="card-body">
                <form id="asetForm" action="{{ route('aset.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_asset">Nama Aset <span class="text-danger">*</span></label>
                        <input type="text" name="nama_asset" class="form-control @error('nama_asset') is-invalid @enderror" required placeholder="Mobil" value="{{ old('nama_asset') }}">
                        @error('nama_asset')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="harga_perolehan">Harga Perolehan<span class="text-danger">*</span></label>
                        <input type="text" name="harga_perolehan" id="harga_perolehan" class="form-control @error('harga_perolehan') is-invalid @enderror" required placeholder="250.000.000">
                        @error('harga_perolehan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nilai_sisa">Nilai Sisa<span class="text-danger">*</span></label>
                        <input type="text" name="nilai_sisa" id="nilai_sisa" class="form-control @error('nilai_sisa') is-invalid @enderror"required placeholder="10.000.000">
                        @error('nilai_sisa')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="masa_manfaat">Masa Manfaat (Tahun) <span class="text-danger">*</span></label>
                        <input type="number" name="masa_manfaat" class="form-control @error('masa_manfaat') is-invalid @enderror" required placeholder="5">
                        @error('masa_manfaat')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tanggal_perolehan">Bulan Perolehan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="input_month form-control @error('tanggal_perolehan') is-invalid @enderror" id="inputmonth" name="tanggal_perolehan"
                                placeholder="Pilih Bulan & Tahun" value="{{ old('tanggal_perolehan') }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="calendarIcon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                        <div class="month-picker" id="monthPicker">
                            <div class="year-selector">
                                <button id="prevYear">&#9665;</button>
                                <span id="year">{{ now()->year }}</span>
                                <button id="nextYear">&#9655;</button>
                            </div>
                            <div class="month-grid" id="months"></div>
                            <button class="select-this-month" id="selectCurrentMonth">Pilih Bulan Ini</button>
                        </div>
                        @error ('tanggal_perolehan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                        <a href="{{ route('aset.index') }}" class="btn btn-danger">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/monthpicker.js') }}"></script>
<script>
    // Fungsi untuk memformat angka dengan ribuan
    function formatNumber(input) {
        let value = input.value.replace(/[^0-9]/g, ''); // Hapus karakter non-angka
        input.value = new Intl.NumberFormat('id-ID').format(value); // Format ribuan
    }

    // Event listener untuk input asuransi
    const asuransiInput = document.getElementById('harga_perolehan');
    asuransiInput.addEventListener('input', function () {
        formatNumber(asuransiInput);
    });

    // Event listener untuk input tarif tetap
    const tarifTetapInput = document.getElementById('nilai_sisa');
    tarifTetapInput.addEventListener('input', function () {
        formatNumber(tarifTetapInput);
    });

    document.getElementById("saveButton").addEventListener("click", function() {
        const form = document.getElementById("asetForm");

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
            return;
        }

        // If client-side validation passes, show SweetAlert
        Swal.fire({
            title: "Create Aset?",
            text: "Are you sure you want to create this Aset?",
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
