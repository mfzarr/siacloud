<?php

namespace App\Models\Transaksi;

use Illuminate\Support\Str;
use App\Models\Masterdata\Produk;
use App\Models\Laporan\StokProduk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembeliandetail extends Model
{
    use HasFactory;

    protected $table = 'pembelian_detail';
    protected $primaryKey = 'id_pembelian_detail';
    protected $guarded = [];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_pembelian_detail)) {
                $model->id_pembelian_detail = Str::uuid();
            }
        });
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }

    public function produkRelation()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function stokProduk()
{
    return $this->hasOne(StokProduk::class, 'id_pembelian_detail', 'id_pembelian_detail');
}

}
