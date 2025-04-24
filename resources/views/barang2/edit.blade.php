@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Edit Produk/Barang</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('barang2.update', $barang->id_barang) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nama">Nama Produk/Barang</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $barang->nama) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="detail">Detail</label>
                                <input type="text" name="detail" class="form-control" value="{{ old('detail', $barang->detail) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="satuan">Satuan</label>
                                <input type="text" name="satuan" class="form-control" value="{{ old('satuan', $barang->satuan) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="harga_jual">Harga Jual</label>
                                <input type="number" name="harga_jual" class="form-control" value="{{ old('harga_jual', $barang->harga_jual) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="HPP">Harga Pokok Penjualan (HPP)</label>
                                <input type="number" name="HPP" class="form-control" value="{{ old('HPP', $barang->HPP) }}" required>
                            </div>

                            <button type="submit" class="btn btn-success">Update Barang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
