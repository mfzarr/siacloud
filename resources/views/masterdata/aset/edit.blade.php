@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Edit Aset</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Aset</a></li>
                                <li class="breadcrumb-item"><a>Edit</a></li>
                            </ul>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Edit Aset</h5>
                </div>
                <div class="card-body">
                    <form id="asetForm" action="{{ route('aset.update', $asset->id_assets) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama_asset">Nama Aset <span class="text-danger">*</span></label>
                            <input type="text" name="nama_asset" class="form-control @error('nama_asset') is-invalid @enderror" value="{{ old('nama_asset', $asset->nama_asset) }}" required>
                            @error('nama_asset')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga_perolehan">Harga Perolehan <span class="text-danger">*</span></label>
                            <input type="text" name="harga_perolehan" id="harga_perolehan" class="form-control @error('harga_perolehan') is-invalid @enderror"
                                value="{{ old('harga_perolehan', number_format($asset->harga_perolehan, 0, ',', '.')) }}" required>
                            <input type="hidden" name="harga_perolehan" id="harga_perolehan_hidden" value="{{ $asset->harga_perolehan }}">
                            @error('harga_perolehan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nilai_sisa">Nilai Sisa <span class="text-danger">*</span></label>
                            <input type="text" name="nilai_sisa" id="nilai_sisa" class="form-control @error('nilai_sisa') is-invalid @enderror"
                                value="{{ old('nilai_sisa', number_format($asset->nilai_sisa, 0, ',', '.')) }}" required>
                            <input type="hidden" name="nilai_sisa" id="nilai_sisa_hidden" value="{{ $asset->nilai_sisa }}">
                            @error('nilai_sisa')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="masa_manfaat">Masa Manfaat (Tahun) <span class="text-danger">*</span></label>
                            <input type="number" name="masa_manfaat" class="form-control @error('masa_manfaat') is-invalid @enderror" value="{{ old('masa_manfaat', $asset->masa_manfaat) }}" required>
                            @error('masa_manfaat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_perolehan">Bulan Perolehan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="input_month form-control @error('tanggal_perolehan') is-invalid @enderror" id="inputmonth" name="tanggal_perolehan"
                                    placeholder="Pilih Bulan & Tahun" value="{{ old('tanggal_perolehan', $asset->tanggal_perolehan ? date('Y-m', strtotime($asset->tanggal_perolehan)) : '') }}" required>
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
                            @error('tanggal_perolehan')
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
        function formatAndSetHiddenField(input, hiddenField) {
            let value = input.value.replace(/[^0-9]/g, ''); // Hapus karakter non-angka
            input.value = new Intl.NumberFormat('id-ID').format(value); // Format angka
            hiddenField.value = value; // Simpan angka murni ke hidden field
        }

        // Event listener untuk harga perolehan
        const hargaPerolehanInput = document.getElementById('harga_perolehan');
        const hargaPerolehanHidden = document.getElementById('harga_perolehan_hidden');
        hargaPerolehanInput.addEventListener('input', function () {
            formatAndSetHiddenField(hargaPerolehanInput, hargaPerolehanHidden);
        });

        // Event listener untuk nilai sisa
        const nilaiSisaInput = document.getElementById('nilai_sisa');
        const nilaiSisaHidden = document.getElementById('nilai_sisa_hidden');
        nilaiSisaInput.addEventListener('input', function () {
            formatAndSetHiddenField(nilaiSisaInput, nilaiSisaHidden);
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
            Swal.fire({
                title: "Validation Error",
                text: "Please fill in all required fields.",
                icon: "error"
            });
            return;
            }

            // If client-side validation passes, show SweetAlert
            Swal.fire({
            title: "Edit Aset?",
            text: "Are you sure you want to edit this asset?",
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
