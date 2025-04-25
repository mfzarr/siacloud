@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">List of Kategori Produk</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('kategori-produk.index') }}">Kategori Produk</a></li>
                            </ul> 
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Kategori Produk List</h5>
                            <div class="float-right">
                                <a href="{{ route('kategori-produk.create') }}"
                                    class="btn btn-success btn-sm btn-round has-ripple"><i class="feather icon-plus"></i>Add
                                    Kategori Produk</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($kategori_barangs->isEmpty())
                                <p>No Kategori Produk found for your perusahaan.</p>
                            @else
                                <div class="table-responsive">
                                    <table id="simpletable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kategori_barangs as $kategori)
                                                <tr>
                                                    <td>{{ $kategori->nama }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="custom-control custom-switch mr-3">
                                                                <input type="checkbox" class="custom-control-input status-switch" 
                                                                    id="statusSwitch{{ $kategori->id_kategori_barang }}" 
                                                                    data-id="{{ $kategori->id_kategori_barang }}"
                                                                    data-product-count="{{ $kategori->produk_count }}"
                                                                    {{ $kategori->status === 'Aktif' ? 'checked' : '' }}>
                                                                <label class="custom-control-label" 
                                                                    for="statusSwitch{{ $kategori->id_kategori_barang }}">
                                                                </label>
                                                            </div>
                                                            <span class="badge {{ $kategori->status === 'Aktif' ? 'badge-success' : 'badge-danger' }}">
                                                                {{ $kategori->status }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('kategori-produk.edit', $kategori->id_kategori_barang) }}"
                                                            class="btn btn-info btn-sm"><i
                                                                class="feather icon-edit"></i>&nbsp;Edit</a>
                                                        <form id="delete-form-{{ $kategori->id_kategori_barang }}"
                                                            action="{{ route('kategori-produk.destroy', $kategori->id_kategori_barang) }}"
                                                            method="POST" style="display:{{ $kategori->status === 'Aktif' ? 'none' : 'inline' }};">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="feather icon-trash-2"></i>&nbsp;Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete confirmation
            document.querySelectorAll('form[id^="delete-form-"]').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus data ini?',
                        text: "Tindakan ini tidak bisa diubah!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus data ini!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Status switch functionality
            document.querySelectorAll('.status-switch').forEach(function(switchEl) {
                switchEl.addEventListener('change', function() {
                    const kategoriId = this.getAttribute('data-id');
                    const isChecked = this.checked;
                    const productCount = parseInt(this.getAttribute('data-product-count') || 0);
                    
                    // If deactivating and there are products, show warning
                    if (!isChecked && productCount > 0) {
                        Swal.fire({
                            title: 'Perhatian!',
                            text: `Kategori ini memiliki ${productCount} produk terkait. Menonaktifkan kategori ini akan menonaktifkan semua produk terkait. Lanjutkan?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, nonaktifkan!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                updateKategoriStatus(kategoriId, isChecked, this);
                            } else {
                                // Revert the switch if user cancels
                                this.checked = !isChecked;
                            }
                        });
                    } else {
                        // If activating or no products, proceed without warning
                        updateKategoriStatus(kategoriId, isChecked, this);
                    }
                });
            });

            function updateKategoriStatus(kategoriId, isChecked, switchElement) {
                // Show loading indicator
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang mengubah status kategori',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request to update status
                fetch(`/kategori-produk/${kategoriId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: isChecked ? 'Aktif' : 'Tidak Aktif'
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update the badge
                    const badgeContainer = switchElement.closest('td').querySelector('.badge');
                    if (isChecked) {
                        badgeContainer.className = 'badge badge-success';
                        badgeContainer.textContent = 'Aktif';
                        
                        // Hide delete button if status is Aktif
                        const row = switchElement.closest('tr');
                        const deleteForm = row.querySelector(`form[id^="delete-form-"]`);
                        if (deleteForm) {
                            deleteForm.style.display = 'none';
                        }
                    } else {
                        badgeContainer.className = 'badge badge-danger';
                        badgeContainer.textContent = 'Tidak Aktif';
                        
                        // Show delete button if status is Tidak Aktif
                        const row = switchElement.closest('tr');
                        const deleteForm = row.querySelector(`form[id^="delete-form-"]`);
                        if (deleteForm) {
                            deleteForm.style.display = 'inline';
                        }
                    }
                    
                    // Show success message with affected products count
                    let successMessage = `Status kategori berhasil diubah menjadi ${isChecked ? 'Aktif' : 'Tidak Aktif'}`;
                    if (data.affected_products > 0) {
                        successMessage += `. ${data.affected_products} produk terkait juga telah diperbarui.`;
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: successMessage,
                        timer: 3000,
                        showConfirmButton: false
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Revert the switch to its previous state
                    switchElement.checked = !isChecked;
                    
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat mengubah status kategori.',
                    });
                });
            }

            // Check for success message in session and show SweetAlert
            @if (session('success'))
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endsection