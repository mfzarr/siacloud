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
                                <h5 class="m-b-10">Create Penjualan</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
                                <li class="breadcrumb-item active"><a>Tambah Penjualan</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Create New Penjualan</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="penjualanForm" action="{{ route('penjualan.store') }}" method="POST">
                        @csrf

                        <div class="form-group" autocomplete="off">
                            <label for="tanggal" class="form-label">Tanggal Penjualan <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="tanggal" name="tanggal"
                                class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}"
                                required>
                            @error('tanggal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="pelanggan" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                            <select id="pelanggan" name="pelanggan"
                                class="form-control @error('pelanggan') is-invalid @enderror" required
                                onchange="updateDiscountInfo()">
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($pelanggan as $item)
                                    @if ($item->status == 'Aktif')
                                        <option value="{{ $item->id_pelanggan }}"
                                            data-jumlah-transaksi="{{ $item->jumlah_transaksi }}">
                                            {{ $item->nama }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('pelanggan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="discount-info" class="alert alert-info d-none">
                            <strong>Discount:</strong>
                            <p id="discount-message">Pilih pelanggan untuk melihat diskon yang tersedia.</p>
                        </div>

                        <h5 class="mt-4">Produk Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="produkTable">
                                <thead>
                                    <tr>
                                        <th>Produk <span class="text-danger">*</span></th>
                                        <th>Harga <span class="text-danger">*</span></th>
                                        <th>Kuantitas <span class="text-danger">*</span></th>
                                        <th>Pegawai <span class="text-danger">*</span></th>
                                        <th>Subtotal</th>
                                        <th>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="addRow()">Add
                                                Row</button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="produk[0][id_produk]"
                                                class="form-control produk-select @error('produk[0][id_produk]') is-invalid @enderror"
                                                onchange="updateHarga(this)" required>
                                                <option value="">Select Produk</option>
                                                @foreach ($produk as $item)
                                                    @if ($item->status == 'Aktif')
                                                        <option value="{{ $item->id_produk }}"
                                                            data-harga="{{ $item->harga }}">{{ $item->nama }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('produk[0][id_produk]')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td><input type="number" name="produk[0][harga]"
                                                class="form-control harga @error('produk[0][harga]') is-invalid @enderror"
                                                readonly required>
                                            @error('produk[0][harga]')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td><input type="number" name="produk[0][kuantitas]"
                                                class="form-control kuantitas @error('produk[0][kuantitas]') is-invalid @enderror"
                                                min="1" onchange="calculateSubtotal(this)" required>
                                            @error('produk[0][kuantitas]')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <select name="produk[0][pegawai]"
                                                class="form-control @error('produk[0][pegawai]') is-invalid @enderror"
                                                required>
                                                <option value="">Select Pegawai</option>
                                                @foreach ($pegawai as $item)
                                                    @if ($item->status == 'Aktif')
                                                        <option value="{{ $item->id_karyawan }}">{{ $item->nama }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('produk[0][pegawai]')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td><input type="number" class="form-control subtotal" readonly></td>
                                        <td><button type="button" class="btn btn-sm btn-danger"
                                                onclick="removeRow(this)">Remove</button></td>
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Total</strong></td>
                                        <td>
                                            <input type="number" id="total" class="form-control" readonly>
                                            <input type="hidden" name="total" id="total_hidden">
                                            <!-- Hidden input for the total -->
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="text-right">
                            <button type="button" id="saveButton" class="btn btn-success mr-2">Create
                                Penjualan</button>
                            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.getElementById("saveButton").addEventListener("click", function() {
            const form = document.getElementById("penjualanForm");

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
                title: "Create Penjualan?",
                text: "Are you sure you want to create this Penjualan?",
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

        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi flatpickr pada input tanggal dengan konfigurasi untuk memastikan input bisa diubah
            flatpickr("#tanggal", {
                dateFormat: "Y-m-d", // Format tanggal Y-m-d
                disableMobile: false, // Memastikan Flatpickr tidak menjadi readonly pada perangkat mobile
                allowInput: true // Memungkinkan input manual langsung ke dalam field
            });
        });

        function updateHarga(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga');
            const row = selectElement.closest('tr');
            row.querySelector('.harga').value = harga; // Set harga value
            calculateSubtotal(row.querySelector('.kuantitas'));
        }

        function calculateSubtotal(kuantitasInput) {
            const row = kuantitasInput.closest('tr');
            const harga = parseFloat(row.querySelector('.harga').value) || 0;
            const kuantitas = parseInt(kuantitasInput.value) || 0;
            const subtotal = harga * kuantitas;
            row.querySelector('.subtotal').value = subtotal;
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(subtotalInput => {
                total += parseFloat(subtotalInput.value) || 0;
            });
            document.getElementById('total').value = total;
            document.getElementById('total_hidden').value = total; // Set hidden input value
        }

        function addRow() {
            const table = document.querySelector("#produkTable tbody");
            const rowCount = table.rows.length;
            const newRow = table.insertRow(rowCount);
            newRow.innerHTML = document.querySelector("#produkTable tbody tr").innerHTML.replace(/\[0\]/g, `[${rowCount}]`);
        }

        function removeRow(button) {
            button.closest('tr').remove();
            calculateTotal(); // Recalculate total when a row is removed
        }

        const discounts = @json($discounts);

        function updateDiscountInfo() {
            const pelangganSelect = document.getElementById('pelanggan');
            const selectedOption = pelangganSelect.options[pelangganSelect.selectedIndex];
            const jumlahTransaksi = parseInt(selectedOption.getAttribute('data-jumlah-transaksi')) || 0;

            const nextTransaction = jumlahTransaksi + 1;
            const discountInfo = discounts[nextTransaction];

            const discountInfoDiv = document.getElementById('discount-info');
            const discountMessage = document.getElementById('discount-message');

            if (discountInfo) {
                discountInfoDiv.classList.remove('d-none');
                discountMessage.innerHTML =
                    `pada transaksi ini (No. ${nextTransaction}), pelanggan akan mendapat <strong>${discountInfo.discount_percentage}%</strong>.`;
            } else {
                discountInfoDiv.classList.add('d-none');
                discountMessage.innerHTML = 'tidak ada diskon.';
            }
        }
    </script>
@endsection
