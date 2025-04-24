<!DOCTYPE html>
<html>
<head>
    <title>Rekap Pembelian</title>
</head>
<body>
    <h1>Rekap Pembelian</h1>
    <table>
        <thead>
            <tr>
                <th>No Transaksi Pembelian</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Total</th>
                <th>Status</th>
                <th>Sisa Hutang</th>
                <th>Produk</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian as $item)
            <tr>
                <td>{{ $item->tanggal_pembelian }}</td>
                <td>{{ $item->supplierRelation->nama_supplier }}</td>
                <td>{{ $item->total }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->rekap ? $item->rekap->sisa_hutang : 0 }}</td>
                <td>{{ $item->no_transaksi_pembelian }}</td>
                <td>{{ $item->produkRelation->nama_produk }}</td>
                <td>{{ $item->qty }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>