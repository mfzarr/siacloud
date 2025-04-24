@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Add Produk/Barang</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('barang2.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nama">Nama Produk/Barang</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="detail">Detail</label>
                                <input type="text" name="detail" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="satuan">Satuan</label>
                                <input type="text" name="satuan" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="harga_jual">Harga Jual</label>
                                <input type="number" name="harga_jual" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="hpp">Harga Pokok Penjualan (HPP)</label>
                                <input type="number" name="HPP" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success">Add Barang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
