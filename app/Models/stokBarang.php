<?php

namespace App\Models;

use App\Models\Transaksi\Pembeliandetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Masterdata\Produk;
use App\Models\Transaksi\PenjualanDetail;

class stokBarang extends Model
{
    use HasFactory;

    protected $table = 'stok_barang';

    protected $primaryKey = 'id_stok';

    protected $guarded = [];

    public function pembelianDetail()
    {
        return $this->hasMany(Pembeliandetail::class, 'id_stok', 'id_stok');
    }

    public function produkRelation()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_stok', 'id_stok');
    }

}
