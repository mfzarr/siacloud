<?php

namespace App\Models\Transaksi;

use Illuminate\Support\Str;
use App\Models\Masterdata\Produk;
use App\Models\Laporan\StokProduk;
use App\Models\Masterdata\Karyawan;
use App\Models\Transaksi\Penjualan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_detail';
    protected $primaryKey = 'id_penjualan_detail';
    protected $guarded = [];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_penjualan_detail)) {
                $model->id_penjualan_detail = Str::uuid();
            }
        });
    }

    /**
     * Relationship with Penjualan.
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id_penjualan');
    }

    public function produkRelation()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function pegawaiRelation()
    {
        return $this->belongsTo(Karyawan::class, 'id_pegawai', 'id_karyawan');
    }

    public function stokProduk()
{
    return $this->hasOne(StokProduk::class, 'id_penjualan_detail', 'id_penjualan_detail');
}
}
