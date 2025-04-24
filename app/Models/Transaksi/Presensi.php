<?php
namespace App\Models\Transaksi;

use App\Models\Perusahaan;
use Illuminate\Support\Str;
use App\Models\Masterdata\Karyawan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    protected $primaryKey = 'id_presensi';

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_presensi)) {
                $model->id_presensi = Str::uuid();
            }
        });
    }
    
    protected $fillable = [
        'id_karyawan', 'tanggal_presensi', 'status', 'id_perusahaan','jam_masuk','jam_keluar',
    ];

    // Relationship: A Presensi belongs to a Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    // Relationship with Perusahaan
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

}
