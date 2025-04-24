<?php

namespace App\Models\Masterdata;

use Illuminate\Support\Str;
use App\Models\Laporan\StokProduk;
use App\Models\Masterdata\Supplier;
use App\Models\Masterdata\Perusahaan;
use Illuminate\Database\Eloquent\Model;
use App\Models\Masterdata\Kategori_barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $primaryKey = 'id_produk';

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_produk)) {
                $model->id_produk = Str::uuid();
            }
        });
    }

    protected $fillable = [
        'nama', 'id_kategori_barang','stok_awal', 'stok', 'harga', 'hpp', 'status', 'id_perusahaan'
    ];
    
    

    public $timestamps = true;

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    public function kategori_barang()
    {
        return $this->belongsTo(Kategori_barang::class, 'id_kategori_barang');
    }

    public function stok_produk()
    {
        return $this->hasMany(stok_produk::class, 'id_produk', 'id_produk');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'produk_supplier', 'id_produk', 'id_supplier');
    }

    public function stokProduk()
{
    return $this->hasMany(StokProduk::class, 'id_produk', 'id_produk');
}
    
}
