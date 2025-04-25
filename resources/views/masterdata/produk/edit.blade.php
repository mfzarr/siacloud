@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Edit Produk</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
                                <li class="breadcrumb-item"><a>Edit Produk</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Produk</h5>
                        </div>
                        <div class="card-body">
                            <form id="produkForm" action="{{ route('produk.update', $produk->id_produk) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="nama">Nama Produk <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        id="nama" name="nama" value="{{ $produk->nama }}" required>
                                    @error('nama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="id_kategori_barang">Kategori Produk <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('id_kategori_barang') is-invalid @enderror"
                                        id="id_kategori_barang" name="id_kategori_barang" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($kategori_barang as $kategori)
                                            <option value="{{ $kategori->id_kategori_barang }}"
                                                {{ $produk->id_kategori_barang == $kategori->id_kategori_barang ? 'selected' : '' }}>
                                                {{ $kategori->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kategori_barang')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="stok">Stok <span class="text-danger">*</span></label>
                                    <input type="text" id="stok" class="form-control @error('stok') is-invalid @enderror"
                                        value="{{ old('stok', number_format($produk->stok, 0, ',', '.')) }}" required>
                                    <input type="hidden" name="stok" id="stok_hidden" value="{{ old('stok', $produk->stok) }}">
                                    @error('stok')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="harga">Harga Jual <span class="text-danger">*</span></label>
                                    <input type="text" id="harga" class="form-control @error('harga') is-invalid @enderror"
                                        value="{{ old('harga', number_format($produk->harga, 0, ',', '.')) }}" required>
                                    <input type="hidden" name="harga" id="harga_hidden" value="{{ old('harga', $produk->harga) }}">
                                    @error('harga')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="hpp">HPP <span class="text-danger">*</span></label>
                                    <input type="text" id="hpp" class="form-control @error('hpp') is-invalid @enderror"
                                        value="{{ old('hpp', number_format($produk->hpp, 0, ',', '.')) }}" required>
                                    <input type="hidden" name="hpp" id="hpp_hidden" value="{{ old('hpp', $produk->hpp) }}">
                                    @error('hpp')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="Aktif" {{ $produk->status == 'Aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="Tidak Aktif"
                                            {{ $produk->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> --}}

                                <div class="text-right">
                                    <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                    <a href="{{ route('produk.index') }}" class="btn btn-danger">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
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

        // Event listener untuk stok
        const stokInput = document.getElementById('stok');
        const stokHidden = document.getElementById('stok_hidden');
        stokInput.addEventListener('input', function () {
            formatAndSetHiddenField(stokInput, stokHidden);
        });

        // Event listener untuk harga
        const hargaInput = document.getElementById('harga');
        const hargaHidden = document.getElementById('harga_hidden');
        hargaInput.addEventListener('input', function () {
            formatAndSetHiddenField(hargaInput, hargaHidden);
        });

        // Event listener untuk hpp
        const hppInput = document.getElementById('hpp');
        const hppHidden = document.getElementById('hpp_hidden');
        hppInput.addEventListener('input', function () {
            formatAndSetHiddenField(hppInput, hppHidden);
        });

        document.getElementById("saveButton").addEventListener("click", function() {
            const form = document.getElementById("produkForm");

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
                title: "Edit Produk?",
                text: "Are you sure you want to edit this Produk?",
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
