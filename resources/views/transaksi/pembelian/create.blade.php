{{-- resources/views/layouts/transaksi/create.blade.php --}}
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
                                <h5 class="m-b-10">Create Pembelian</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}">Pembelian</a></li>
                                <li class="breadcrumb-item"><a href="#!">Create Pembelian</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Create New Pembelian</h5>
                        </div>
                        <div class="card-body">
                            {{-- Display errors if any --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form id="pembelianForm" action="{{ route('pembelian.store') }}" method="POST">
                                @csrf
                                {{-- Tanggal Pembelian --}}
                                <div class="form-group" autocomplete="off">
                                    <label for="tanggal" class="form-label">Tanggal Pembelian <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="tanggal" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ old('tanggal') }}" required>
                                    @error('tanggal')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Supplier --}}
                                <div class="form-group">
                                    <label for="supplier" class="form-label">Supplier <span
                                            class="text-danger">*</span></label>
                                    <select id="supplier" name="supplier"
                                        class="form-control @error('supplier') is-invalid @enderror" required
                                        onchange="updateProductOptions()">
                                        <option value="">Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            @if ($supplier->status == 'Aktif')
                                                <option value="{{ $supplier->id_supplier }}"
                                                    data-products="{{ json_encode($supplier->products) }}">
                                                    {{ $supplier->nama }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('supplier')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Tipe Pembayaran --}}
                                <div class="form-group">
                                    <label for="tipe_pembayaran" class="form-label">Tipe Pembayaran <span
                                            class="text-danger">*</span></label>
                                    <select id="tipe_pembayaran" name="tipe_pembayaran"
                                        class="form-control @error('tipe_pembayaran') is-invalid @enderror" required
                                        onchange="handleTipePembayaranChange()">
                                        <option value="" hidden selected>Pilih Tipe Pembayaran</option>
                                        <option value="tunai">Tunai</option>
                                        <option value="kredit">Kredit</option>
                                    </select>
                                    @error('tipe_pembayaran')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Table for produk Details --}}
                                <h5 class="mt-4">Produk Details</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="produkTable">
                                        <thead>
                                            <tr>
                                                <th>Produk <span class="text-danger">*</span></th>
                                                <th>Qty <span class="text-danger">*</span></th>
                                                <th>Harga <span class="text-danger">*</span></th>
                                                <th>Subtotal <span class="text-danger">*</span></th>
                                                <th>Dibayar <span class="text-danger">*</span></th>
                                                <th>
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        onclick="addRow()">Add Row</button>
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
                                                                    data-harga="{{ $item->hpp }}">{{ $item->nama }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @error('produk[0][id_produk]')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td><input type="number" name="produk[0][qty]"
                                                        class="form-control qty @error('produk[0][qty]') is-invalid @enderror"
                                                        required>
                                                    @error('produk[0][qty]')
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
                                                <td><input type="number" name="produk[0][subtotal]"
                                                        class="form-control subtotal @error('produk[0][subtotal]') is-invalid @enderror"
                                                        readonly required>
                                                    @error('produk[0][subtotal]')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td><input type="number" name="produk[0][dibayar]"
                                                        class="form-control dibayar @error('produk[0][dibayar]') is-invalid @enderror"
                                                        required>
                                                    @error('produk[0][dibayar]')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td><button type="button" class="btn btn-sm btn-danger"
                                                        onclick="removeRow(this)">Remove</button></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>Total Dibayar</strong></td>
                                                <td colspan="3"></td>
                                                <td><input type="number" id="total_dibayar" class="form-control"
                                                        readonly>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Total</strong></td>
                                                <td colspan="2"></td>
                                                <td><input type="number" id="total" class="form-control" readonly>
                                                </td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                {{-- Submit --}}
                                <div class="text-right">
                                    <button type="button" id="saveButton" class="btn btn-success mr-2">Create
                                        Pembelian</button>
                                    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{-- Script to handle row addition, deletion, and calculations --}}
    <script>
        document.getElementById("saveButton").addEventListener("click", function() {
            const form = document.getElementById("pembelianForm");

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
                title: "Create Pembelian?",
                text: "Are you sure you want to create this Pembelian?",
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

        function handleTipePembayaranChange() {
            const tipePembayaran = document.getElementById('tipe_pembayaran').value;
            const dibayarInputs = document.querySelectorAll('[name*="dibayar"]');
            const subtotalInputs = document.querySelectorAll('.subtotal');

            dibayarInputs.forEach((input, index) => {
                if (tipePembayaran === 'tunai') {
                    input.value = subtotalInputs[index].value;
                    input.readOnly = true;
                } else {
                    input.readOnly = false;
                }
            });

            calculateTotals();
        }

        function updateProductOptions() {
            const supplierSelect = document.getElementById('supplier');
            const selectedSupplier = supplierSelect.options[supplierSelect.selectedIndex];
            const products = JSON.parse(selectedSupplier.dataset.products || '[]');

            // Update all product dropdowns
            document.querySelectorAll('.produk-select').forEach(select => {
                // Save the current selection
                const currentSelection = select.value;

                // Clear existing options
                select.innerHTML = '<option value="">Select Produk</option>';

                // Add new options based on the selected supplier and status
                products.forEach(product => {
                    if (product.status === 'Aktif') {
                        const option = document.createElement('option');
                        option.value = product.id_produk;
                        option.textContent = product.nama;
                        option.dataset.harga = product.hpp;
                        select.appendChild(option);
                    }
                });

                // Restore the previous selection if it's still available
                if (currentSelection) {
                    const option = select.querySelector(`option[value="${currentSelection}"]`);
                    if (option) {
                        option.selected = true;
                    }
                }

                // Trigger the change event to update the price
                select.dispatchEvent(new Event('change'));
            });
        }


        function updateHarga(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga');
            const row = selectElement.closest('tr');
            row.querySelector('.harga').value = harga; // Set harga value
            calculateSubtotal(row.querySelector('.kuantitas'));
        }
        document.addEventListener('DOMContentLoaded', function() {
            updateProductOptions();
        });

        // Modify the addRow function to include the updateProductOptions call
        function addRow() {
            const table = document.getElementById("produkTable").getElementsByTagName('tbody')[0];
            const rowCount = table.rows.length;
            const row = table.insertRow(rowCount);
            row.innerHTML = document.querySelector("#produkTable tbody tr").innerHTML.replace(/\[0\]/g, `[${rowCount}]`);
            updateProductOptions(); // Update options for the new row
            handleTipePembayaranChange();
        }

        function removeRow(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            calculateTotals();
        }

        document.getElementById('tipe_pembayaran').addEventListener('change', function() {
            const isTunai = this.value === 'tunai';

            // Loop through all rows and update 'dibayar' field based on payment type
            document.querySelectorAll('#produkTable tbody tr').forEach(row => {
                const dibayarInput = row.querySelector('[name*="dibayar"]');
                const subtotalInput = row.querySelector('.subtotal');

                if (isTunai) {
                    dibayarInput.value = subtotalInput.value; // Set dibayar to subtotal
                    dibayarInput.setAttribute('readonly', true); // Make it uneditable
                } else {
                    dibayarInput.value = ''; // Clear value if kredit is selected
                    dibayarInput.removeAttribute('readonly'); // Make it editable
                }
            });

            calculateTotals(); // Update totals after changing payment type
        });

        document.addEventListener('input', function(event) {
            if (event.target.classList.contains('qty') || event.target.classList.contains('harga')) {
                const row = event.target.closest('tr');
                const qty = parseFloat(row.querySelector('.qty').value) || 0;
                const harga = parseFloat(row.querySelector('.harga').value) || 0;
                const subtotal = qty * harga;
                row.querySelector('.subtotal').value = subtotal;

                // Automatically update 'dibayar' if payment type is tunai
                const tipePembayaran = document.getElementById('tipe_pembayaran').value;
                const dibayarInput = row.querySelector('[name*="dibayar"]');
                if (tipePembayaran === 'tunai') {
                    dibayarInput.value = subtotal;
                }

                calculateTotals(); // Recalculate totals on input change
            }
        });

        function calculateTotals() {
            let total = 0;
            let total_dibayar = 0;

            document.querySelectorAll('#produkTable tbody tr').forEach(row => {
                total += parseFloat(row.querySelector('.subtotal').value) || 0;
                total_dibayar += parseFloat(row.querySelector('[name*="dibayar"]').value) || 0;
            });

            document.getElementById('total').value = total;
            document.getElementById('total_dibayar').value = total_dibayar;
        }

        document.getElementById('produk[0][id_produk]').addEventListener('change', function() {
            var id_produk = this.value;

            fetch(`/get-harga/${id_produk}`)
                .then(response => response.json())
                .then(data => {
                    document.querySelector('.harga').value = data.harga;
                });
        }); // Trigger change event on page load

        document.addEventListener('DOMContentLoaded', function() {
            updateProductOptions();
            handleTipePembayaranChange();
        });

        // document.getElementById("saveButton").addEventListener("click", function() {
        //     const form = document.getElementById("pembelianForm");

        //     // Perform client-side validation
        //     let isValid = true;
        //     const requiredFields = form.querySelectorAll('[required]');
        //     requiredFields.forEach(field => {
        //         if (!field.value.trim()) {
        //             isValid = false;
        //             field.classList.add('is-invalid');
        //         } else {
        //             field.classList.remove('is-invalid');
        //         }
        //     });

        //     if (!isValid) {
        //         Swal.fire({
        //             title: "Validation Error",
        //             text: "Please fill in all required fields.",
        //             icon: "error"
        //         });
        //         return;
        //     }

        //     // If client-side validation passes, show SweetAlert
        //     Swal.fire({
        //         title: "Create Pembelian?",
        //         text: "Are you sure you want to create this Pembelian?",
        //         icon: "question",
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, create it!'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // Submit the form
        //             form.submit();
        //         }
        //     });
        // });

        // // Check for success message in session and show SweetAlert
        // @if (session('success'))
        //     Swal.fire({
        //         title: "Success!",
        //         text: "{{ session('success') }}",
        //         icon: "success",
        //         draggable: true
        //     });
        // @endif
    </script>
@endsection
