@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">List of Produk</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Produk List</h5>
                            <div class="row align-items-center m-l-0">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6 text-right">
                                    <a href="{{ route('produk.create') }}"
                                        class="btn btn-success btn-sm btn-round has-ripple"><i
                                            class="feather icon-plus"></i>Add
                                        Produk</a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($produk->isEmpty())
                                    <p>No produks found for your perusahaan.</p>
                                @else
                                    <div class="table-responsive">
                                        <table id="simpletable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Nama Produk</td>
                                                    <td>Kategori</td>
                                                    <td>Stok</td>
                                                    <td>Harga Jual</td>
                                                    <td>Harga Beli (HPP)</td>
                                                    <td>Status</td>
                                                    <td>Actions</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($produk as $produk)
                                                    <tr class="produk-row" data-stok="{{ $produk->stok }}"
                                                        data-nama="{{ $produk->nama }}">
                                                        <td class="align-middle">{{ $produk->nama }}</td>
                                                        <td class="align-middle">{{ $produk->kategori_barang->nama }}</td>
                                                        <td>
                                                            <div class="mb-0">
                                                                <span>{{ $produk->stok }}</span>
                                                                <div class="progress">
                                                                    @php
                                                                        $percentage = ($produk->stok / 100) * 100;
                                                                        $progressClass = 'bg-success';
                                                                        if ($percentage < 25) {
                                                                            $progressClass = 'bg-danger';
                                                                        } elseif ($percentage < 50) {
                                                                            $progressClass = 'bg-warning';
                                                                        } elseif ($percentage < 75) {
                                                                            $progressClass = 'bg-info';
                                                                        }
                                                                    @endphp
                                                                    <div class="progress-bar {{ $progressClass }}"
                                                                        role="progressbar"
                                                                        style="width: {{ $percentage }}%;"
                                                                        aria-valuenow="{{ $produk->stok }}"
                                                                        aria-valuemin="0" aria-valuemax="1000"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            Rp{{ number_format($produk->harga, 0, ',', '.') }}</td>
                                                        <td class="align-middle">
                                                            Rp{{ number_format($produk->hpp, 0, ',', '.') }}</td>
                                                        <td class="align-middle">
                                                            <div class="d-flex align-items-center">
                                                                <div class="custom-control custom-switch mr-0">
                                                                    <input type="checkbox"
                                                                        class="custom-control-input status-switch"
                                                                        id="statusSwitch{{ $produk->id_produk }}"
                                                                        data-id="{{ $produk->id_produk }}"
                                                                        {{ $produk->status === 'Aktif' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label"
                                                                        for="statusSwitch{{ $produk->id_produk }}"></label>
                                                                </div>
                                                                @if ($produk->status === 'Aktif')
                                                                    <span class="badge badge-success">Aktif</span>
                                                                @else
                                                                    <span class="badge badge-danger">Tidak Aktif</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('produk.edit', $produk->id_produk) }}"
                                                                class="btn btn-info btn-sm">
                                                                <i class="feather icon-edit"></i>&nbsp;Edit
                                                            </a>
                                                            <form id="delete-form-{{ $produk->id_produk }}"
                                                                action="{{ route('produk.destroy', $produk->id_produk) }}"
                                                                method="POST"
                                                                style="display:{{ $produk->status === 'Aktif' ? 'none' : 'inline' }};">
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
            document.addEventListener('DOMContentLoaded', function() {
                // Delete confirmation
                document.querySelectorAll('form[id^="delete-form-"]').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
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
                        const produkId = this.getAttribute('data-id');
                        const isChecked = this.checked;
                        const badgeContainer = this.closest('td').querySelector('.badge');

                        // Show loading indicator
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang mengubah status produk',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Send AJAX request to update status
                        fetch(`/produk/${produkId}/update-status`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
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
                                if (isChecked) {
                                    badgeContainer.className = 'badge badge-success';
                                    badgeContainer.textContent = 'Aktif';

                                    // Hide delete button if status is Aktif
                                    const row = this.closest('tr');
                                    const deleteForm = row.querySelector(
                                    `form[id^="delete-form-"]`);
                                    if (deleteForm) {
                                        deleteForm.style.display = 'none';
                                    }
                                } else {
                                    badgeContainer.className = 'badge badge-danger';
                                    badgeContainer.textContent = 'Tidak Aktif';

                                    // Show delete button if status is Tidak Aktif
                                    const row = this.closest('tr');
                                    const deleteForm = row.querySelector(
                                    `form[id^="delete-form-"]`);
                                    if (deleteForm) {
                                        deleteForm.style.display = 'inline';
                                    }
                                }

                                // Show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: `Status produk berhasil diubah menjadi ${isChecked ? 'Aktif' : 'Tidak Aktif'}`,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            })
                            .catch(error => {
                                console.error('Error:', error);

                                // Revert the switch to its previous state
                                this.checked = !isChecked;

                                // Show error message
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat mengubah status produk'
                                });
                            });
                    });
                });

                // Low stock notifications
                @if ($lowStockProduk->isNotEmpty())
                    Swal.fire({
                        title: 'Peringatan Stok Rendah',
                        html: `
                            <div class="text-left">
                                <p>Beberapa produk memiliki stok yang rendah:</p>
                                <ul>
                                    @foreach ($lowStockProduk as $produk)
                                        <li><strong>{{ $produk->nama }}</strong>: {{ $produk->stok }} tersisa</li>
                                    @endforeach
                                </ul>
                            </div>
                        `,
                        icon: 'warning',
                        confirmButtonText: 'Saya Mengerti'
                    });
                @endif
            });
        </script>
    </div>
@endsection