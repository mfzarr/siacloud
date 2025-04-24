<?php

namespace App\Models\Laporan;

use Illuminate\Support\Str;
use App\Models\Masterdata\Produk;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi\Pembeliandetail;
use App\Models\Transaksi\PenjualanDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokProduk extends Model
{
    use HasFactory;

    protected $table = 'produk_stok';
    protected $primaryKey = 'id_stok_produk';
    protected $guarded = [];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id_stok_produk = (string) Str::uuid();
        });
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function penjualanDetail()
    {
        return $this->belongsTo(PenjualanDetail::class, 'id_penjualan_detail', 'id_penjualan_detail');
    }

    public function pembelianDetail()
    {
        return $this->belongsTo(Pembeliandetail::class, 'id_pembelian_detail', 'id_pembelian_detail');
    }
}