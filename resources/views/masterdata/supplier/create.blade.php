@extends('layouts.frontend')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Add Supplier</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('supplier.index') }}">Supplier</a></li>
                                <li class="breadcrumb-item active">Tambah Supplier</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Create Supplier</h5>
                        </div>
                        <div class="card-body">
                            <form id="supplierForm" action="{{ route('supplier.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="nama">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        id="nama" name="nama" value="{{ old('nama') }}" required>
                                    @error('nama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="no_telp">No Telp <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('no_telp') is-invalid @enderror"
                                        id="no_telp" name="no_telp" value="{{ old('no_telp') }}" required>
                                    @error('no_telp')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                        required>{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="products">Produk <span class="text-danger">*</span></label>
                                    <select class="form-select @error('products') is-invalid @enderror"
                                        id="multiple-select-field" name="products[]" data-placeholder="Pilih Produk"
                                        multiple required>
                                        @php
                                            $groupedProducts = $products->groupBy('kategori_barang.nama');
                                        @endphp
                                        @foreach ($groupedProducts as $category => $products)
                                            @if ($products->count() > 1)
                                                <optgroup label="{{ $category }}">
                                                    @foreach ($products as $product)
                                                        @if ($product->status == 'Aktif')
                                                            <option value="{{ $product->id_produk }}">{{ $product->nama }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                @foreach ($products as $product)
                                                    @if ($product->status == 'Aktif')
                                                        <option value="{{ $product->id_produk }}">{{ $product->nama }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('products')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                        id="single-select-field" name="status" required>
                                        <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>
                                            Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                    <a href="{{ route('supplier.index') }}" class="btn btn-danger">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#multiple-select-field').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
        });

        document.getElementById("saveButton").addEventListener("click", function() {
            const form = document.getElementById("supplierForm");

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
                title: "Create Supplier?",
                text: "Are you sure you want to create this supplier?",
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
