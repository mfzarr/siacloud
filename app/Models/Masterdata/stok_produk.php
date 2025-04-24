<?php

namespace App\Models\Masterdata;

use Illuminate\Support\Str;
use App\Models\Masterdata\Produk;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi\Pembeliandetail;
use App\Models\Transaksi\PenjualanDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class stok_produk extends Model
{
    use HasFactory;

    protected $table ='stok_produk';

    protected $primaryKey = 'id_stok_produk';

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_stok_produk)) {
                $model->id_stok_produk = Str::uuid();
            }
        });
    }

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
