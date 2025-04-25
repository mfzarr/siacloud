<?php

namespace App\Models\Masterdata;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori_barang extends Model
{
    use HasFactory;

    protected $table = 'kategori_barang'; // maps to 'kategori_barang' table

    protected $primaryKey = 'id_kategori_barang'; // set the primary key

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_kategori_barang)) {
                $model->id_kategori_barang = Str::uuid();
            }
        });
    }

    protected $fillable = [
        'nama',
        'id_perusahaan',
    ]; // mass-assignable attributes

    public $timestamps = true; // if you are using created_at and updated_at columns

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'id_kategori_barang', 'id_kategori_barang');
    }
    
}
