<?php

namespace App\Models\Masterdata;

use Illuminate\Support\Str;
use App\Models\Laporan\JurnalUmum;
use App\Models\Transaksi\Penjualan;
use App\Models\Masterdata\CoaKelompok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coa extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'coa';

    // Primary key
    protected $primaryKey = 'id_coa';


    // Data type of the primary key
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_coa)) {
                $model->id_coa = Str::uuid();
            }
        });
    }

    // Fillable columns for mass assignment
    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'kelompok_akun',
        'tanggal_saldo_awal',
        'posisi_d_c',
        'saldo_awal',
        'status',
        'id_perusahaan',
    ];

    // Disable timestamps if not present in the table
    public $timestamps = false;

    /**
     * Define a many-to-one relationship with CoaKelompok
     * (Each Coa belongs to one CoaKelompok)
     */
    public function kelompokakun()
    {
        return $this->belongsTo(CoaKelompok::class, 'kelompok_akun', 'kelompok_akun');
    }

    /**
     * Define a many-to-one relationship with Perusahaan
     * Uncomment if you need it in the future.
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function jurnalUmums()
    {
        return $this->hasMany(JurnalUmum::class, 'id_coa', 'id_coa');
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'id_perusahaan', 'id_perusahaan');
    }
}
