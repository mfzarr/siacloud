@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Selesaikan Penjualan</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
                            <li class="breadcrumb-item active"><a>Edit Penjualan</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Edit Penjualan</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('penjualan.updateSelesai', $penjualan->id_penjualan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h5>Produk Details</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="produkTable">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Kuantitas</th>
                                    <th>Pegawai</th>
                                    <th>Subtotal</th>
                                    <th>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="addRow()">Tambah</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penjualan->penjualanDetails as $index => $detail)
                                <tr>
                                    <td>
                                        <select name="produk[0][id_produk]" class="form-control produk-select" onchange="updateHarga(this)" required>
                                            <option value="">Pilih Produk</option>
                                            @foreach($produk as $item)
                                            <option value="{{ $item->id_produk }}" data-harga="{{ $item->harga }}" {{ $item->id_produk == $detail->id_produk ? 'selected' : '' }}>
                                                {{ $item->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="produk[0][harga]" class="form-control harga" value="{{ $detail->harga }}" readonly required></td>
                                    <td><input type="number" name="produk[0][kuantitas]" class="form-control kuantitas" value="{{ $detail->kuantitas }}" min="1" onchange="calculateSubtotal(this)" required></td>
                                    <td>
                                        <select name="produk[0][pegawai]" class="form-control" required>
                                            <option value="">Pilih Pegawai</option>
                                            @foreach($pegawai as $item)
                                            <option value="{{ $item->id_karyawan }}" {{ $item->id_karyawan == $detail->id_pegawai ? 'selected' : '' }}>
                                                {{ $item->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control subtotal" value="{{ $detail->harga * $detail->kuantitas }}" readonly></td>
                                    <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Hapus</button></td>
                                </tr>

                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                    <td><input type="number" id="total" class="form-control" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-success mt-3">Selesaikan Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateHarga(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const harga = selectedOption.getAttribute('data-harga');
        const row = selectElement.closest('tr');
        row.querySelector('.harga').value = harga;
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
    }

    function addRow() {
        const table = document.querySelector("#produkTable tbody");
        const rowCount = table.rows.length;
        const newRow = table.insertRow(rowCount);
        newRow.innerHTML = document.querySelector("#produkTable tbody tr").innerHTML.replace(/\[\d+\]/g, `[${rowCount}]`);
        newRow.querySelectorAll('input, select').forEach(input => input.value = '');
    }

    function removeRow(button) {
        button.closest('tr').remove();
        calculateTotal();
    }
</script>
@endsection