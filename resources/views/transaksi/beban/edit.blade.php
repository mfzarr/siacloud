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
                            <h5 class="m-b-10">Edit Pengeluaran Beban</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                        class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('beban.index') }}">Pengeluaran Beban</a></li>
                            <li class="breadcrumb-item"><a>Edit Beban</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Form Edit Beban</h5>
            </div>

            <div class="card-body">
                <form id="bebanForm" action="{{ route('beban.update', $beban->id_beban) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_beban">Nama Beban <span class="text-danger">*</span></label>
                        <input type="text" name="nama_beban" id="nama_beban" value="{{ old('nama_beban', $beban->nama_beban) }}" class="form-control @error('nama_beban') is-invalid @enderror" required>
                        @error('nama_beban')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="harga">Nominal <span class="text-danger">*</span></label>
                        <input type="text" id="harga" class="form-control @error('harga') is-invalid @enderror"
                            value="{{ old('harga', number_format($beban->harga, 0, ',', '.')) }}" required>
                        <input type="hidden" name="harga" id="harga_hidden" value="{{ old('harga', $beban->harga) }}">
                        @error('harga')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" autocomplete="off">
                        <label for="tanggal" class="form-label">Tanggal<span class="text-danger">*</span></label>
                        <input type="text" id="tanggal" name="tanggal" value="{{ old('tanggal', $beban->tanggal) }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                        @error('tanggal')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="id_coa">Akun Beban <span class="text-danger">*</span></label>
                        <select name="id_coa" id="id_coa" class="form-control @error('id_coa') is-invalid @enderror" required>
                            <option value="">Pilih Akun</option>
                            @foreach($coa->groupBy(function($item) {
                                \Carbon\Carbon::setLocale('id');
                                return \Carbon\Carbon::parse($item->tanggal_saldo_awal)->translatedFormat('F Y');
                            }) as $tanggal => $akuns)
                            <optgroup label="{{ $tanggal }}">
                                @foreach($akuns as $akun)
                                <option value="{{ $akun->id_coa }}" data-tanggal="{{ \Carbon\Carbon::parse($akun->tanggal_saldo_awal)->format('Y-m') }}" {{ old('id_coa', $beban->id_coa) == $akun->id_coa ? 'selected' : '' }}>
                                    {{ $akun->nama_akun }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        @error('id_coa')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="id_perusahaan" id="id_perusahaan" value="{{ auth()->user()->id_perusahaan }}">
                    <div class="text-right">
                        <button type="button" id="saveButton" class="btn btn-success mr-2">Update Beban</button>
                        <a href="{{ route('beban.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
        function formatAndSetHiddenField(input, hiddenField) {
            let value = input.value.replace(/[^0-9]/g, ''); // Hapus karakter non-angka
            input.value = new Intl.NumberFormat('id-ID').format(value); // Format angka
            hiddenField.value = value; // Simpan angka murni ke hidden field
        }

    // Event listener untuk input harga
    const hargaInput = document.getElementById('harga');
        const hargaHidden = document.getElementById('harga_hidden');
        hargaInput.addEventListener('input', function () {
            formatAndSetHiddenField(hargaInput, hargaHidden);
        });

    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#tanggal", {
            dateFormat: "Y-m-d",
            disableMobile: false, // Memastikan Flatpickr tidak menjadi readonly pada perangkat mobile
            allowInput: true, // Memastikan input tidak readonly pada desktop
            onChange: function(selectedDates, dateStr, instance) {
                filterAkunBeban(dateStr);
            }
        });
        document.getElementById("saveButton").addEventListener("click", function() {
            const form = document.getElementById("bebanForm");

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
                title: "Update Beban?",
                text: "Are you sure you want to update this Beban?",
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

        function filterAkunBeban(selectedDate) {
            const selectedMonth = selectedDate.slice(0, 7); // Get YYYY-MM
            const akunOptions = document.querySelectorAll('#id_coa option');
            const optgroups = document.querySelectorAll('#id_coa optgroup');

            optgroups.forEach(optgroup => {
                let hasVisibleOption = false;
                const options = optgroup.querySelectorAll('option');
                options.forEach(option => {
                    const optionMonth = option.getAttribute('data-tanggal');
                    if (optionMonth === selectedMonth || option.value === "") {
                        option.style.display = 'block';
                        hasVisibleOption = true;
                    } else {
                        option.style.display = 'none';
                    }
                });
                optgroup.style.display = hasVisibleOption ? 'block' : 'none';
            });
        }
    });
</script>
@endsection
