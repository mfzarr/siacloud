@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Edit COA</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('coa.index') }}">COA</a></li>
                                <li class="breadcrumb-item"><a>Edit COA</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Edit COA</h5>
                </div>

                <div class="card-body">
                    <form id="coaForm" method="POST" action="{{ route('coa.update', $coas->id_coa) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="kode_akun">Kode Akun: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_akun') is-invalid @enderror"
                                id="kode_akun" name="kode_akun" value="{{ old('kode_akun', $coas->kode_akun) }}" required
                                readonly>
                            @error('kode_akun')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nama_akun">Nama Akun: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_akun') is-invalid @enderror"
                                id="nama_akun" name="nama_akun" value="{{ old('nama_akun', $coas->nama_akun) }}" 
                                required {{ $coas->status == 'seeder' || $coas->status == 'neraca' ? 'readonly' : '' }}>
                            @error('nama_akun')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kelompok_akun">Kelompok Akun: <span class="text-danger">*</span></label>
                            <select class="form-control @error('kelompok_akun') is-invalid @enderror" id="kelompok_akun"
                                name="kelompok_akun" required disabled>
                                <option value="" selected hidden>Pilih Kelompok</option>
                                @foreach ($kelompokakun as $option)
                                    <option value="{{ $option->kelompok_akun }}"
                                        {{ old('kelompok_akun', $coas->kelompok_akun) == $option->kelompok_akun ? 'selected' : '' }}>
                                        {{ $option->nama_kelompok_akun }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="kelompok_akun"
                                value="{{ old('kelompok_akun', $coas->kelompok_akun) }}">
                            @error('kelompok_akun')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="posisi_d_c">Posisi: <span class="text-danger">*</span></label><br>
                            <input type="radio" id="Debit" name="posisi_d_c" value="Debit"
                                {{ old('posisi_d_c', $coas->posisi_d_c) == 'Debit' ? 'checked' : '' }} disabled> Debit
                            <input type="radio" id="Kredit" name="posisi_d_c" value="Kredit"
                                {{ old('posisi_d_c', $coas->posisi_d_c) == 'Kredit' ? 'checked' : '' }} disabled> Kredit
                            <input type="hidden" name="posisi_d_c" value="{{ old('posisi_d_c', $coas->posisi_d_c) }}">
                            @error('posisi_d_c')
                                <div class="text-danger d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="saldo_awal">Saldo Awal: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('saldo_awal') is-invalid @enderror"
                                id="saldo_awal" name="saldo_awal_display" 
                                value="{{ old('saldo_awal_display', number_format($coas->saldo_awal, 0, ',', '.')) }}"
                                required>
                            <input type="hidden" id="saldo_awal_actual" name="saldo_awal" value="{{ old('saldo_awal', $coas->saldo_awal) }}">
                            @error('saldo_awal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tanggal_saldo_awal">Bulan Saldo Awal: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="input_month form-control @error('tanggal_saldo_awal') is-invalid @enderror" 
                                    id="inputmonth" name="tanggal_saldo_awal"
                                    placeholder="Pilih Bulan & Tahun" 
                                    value="{{ old('tanggal_saldo_awal', $coas->tanggal_saldo_awal ? \Carbon\Carbon::parse($coas->tanggal_saldo_awal)->format('Y-m') : '') }}" 
                                    required>
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
                            @error('tanggal_saldo_awal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <input type="hidden" name="id_perusahaan" value="{{ auth()->user()->perusahaan->id_perusahaan }}">

                        <div class="text-right">
                            <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                            <button type="button" class="btn btn-danger"
                                onclick="window.location='{{ route('coa.index') }}'">Back</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/monthpicker.js') }}"></script>
    <script>
        function setPosisiDC() {
            const kelompokAkun = parseInt(document.getElementById("kelompok_akun").value, 10);
            const debitRadio = document.getElementById("Debit");
            const kreditRadio = document.getElementById("Kredit");

            debitRadio.checked = false;
            kreditRadio.checked = false;

            if (kelompokAkun === 1) {
                debitRadio.checked = true;
            } else if (kelompokAkun >= 2 && kelompokAkun < 5) {
                kreditRadio.checked = true;
            } else if (kelompokAkun >= 5) {
                debitRadio.checked = true;
            }
        }

        document.getElementById("kelompok_akun").addEventListener("change", setPosisiDC);

        // Format saldo awal input
        document.getElementById('saldo_awal').addEventListener('input', function(e) {
            // Remove all non-digit characters
            let value = this.value.replace(/[^0-9]/g, '');
            
            // Convert to number
            let numberValue = parseInt(value || 0);
            
            // Update the hidden field with the actual number value
            document.getElementById('saldo_awal_actual').value = numberValue;
            
            // Format with thousand separators
            this.value = numberValue.toLocaleString('id-ID');
        });

        // Set initial value if there's old input
        @if(old('saldo_awal', $coas->saldo_awal))
            document.getElementById('saldo_awal').value = parseInt({{ old('saldo_awal', $coas->saldo_awal) }}).toLocaleString('id-ID');
        @endif

        document.getElementById("saveButton").addEventListener("click", function() {
            const form = document.getElementById("coaForm");

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
                title: "Update COA?",
                text: "Are you sure you want to update this COA?",
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