<?php

namespace App\Models\Transaksi;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PelunasanPembelian extends Model
{
    use HasFactory;

    protected $table = 'pelunasan_pembelian'; // Match the table name in the database
    protected $primaryKey = 'id_pelunasan'; // Match the primary key in the database

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(callback: function ($model) {
            if (empty($model->id_pelunasan)) {
                $model->id_pelunasan = Str::uuid();
            }
        });
    }
    protected $fillable = [
        'id_pembelian',
        'id_produk',
        'total_pelunasan',
        'tanggal_pelunasan'
    ];

    // Relationship with Pembelian
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }
}
