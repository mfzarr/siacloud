@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Add Produk</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
                            <li class="breadcrumb-item"><a>Add Produk</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Create Produk</h5>
                    </div>
                    <div class="card-body">
                        <form id="produkForm" action="{{ route('produk.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nama">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Ciki Enak">
                                @error('nama')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="id_kategori_barang">Kategori Produk <span class="text-danger">*</span></label>
                                <select class="form-control @error('id_kategori_barang') is-invalid @enderror" id="id_kategori_barang" name="id_kategori_barang" required>
                                    <option value="" >-- Pilih Kategori --</option>
                                    @foreach($kategori_barang as $kategori)
                                        <option value="{{ $kategori->id_kategori_barang }}" {{ old('id_kategori_barang') == $kategori->id_kategori_barang ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                                @error('id_kategori_barang')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>                            

                            <div class="form-group">
                                <label for="stok">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" value="{{ old('stok') }}" required placeholder="100">
                                @error('stok')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="harga">Harga Jual <span class="text-danger">*</span></label>
                                <input type="text" class="form-control format-number @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga') }}" required placeholder="10.000">
                                @error('harga')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="hpp">HPP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control format-number @error('hpp') is-invalid @enderror" id="hpp" name="hpp" value="{{ old('hpp') }}" required placeholder="5.000">
                                @error('hpp')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

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
    function formatNumber(input) {
        let value = input.value.replace(/[^0-9]/g, ''); // Hapus karakter non-angka
        input.value = new Intl.NumberFormat('id-ID').format(value); // Format ribuan
    }

    const stokInput = document.getElementById('stok');
    stokInput.addEventListener('input', function () {
        formatNumber(stokInput);
    });

    const hargaInput = document.getElementById('harga');
    hargaInput.addEventListener('input', function () {
        formatNumber(hargaInput);
    });

    const hppInput = document.getElementById('hpp');
    hppInput.addEventListener('input', function () {
        formatNumber(hppInput);
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
            return;
        }

        // If client-side validation passes, show SweetAlert
        Swal.fire({
            title: "Create Produk?",
            text: "Are you sure you want to create this Produk?",
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
