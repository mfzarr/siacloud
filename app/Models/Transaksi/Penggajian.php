<?php

namespace App\Models\Transaksi;

use Illuminate\Support\Str;
use App\Models\Masterdata\Karyawan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penggajian extends Model
{
    use HasFactory;

    protected $table = 'penggajian';
    protected $primaryKey = 'id_gaji';
    protected $guarded = [];
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_gaji)) {
                $model->id_gaji = (string) Str::uuid();
            }
        });
    }

    // Update foreign key to match the database field `id_pegawai`
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }
}
