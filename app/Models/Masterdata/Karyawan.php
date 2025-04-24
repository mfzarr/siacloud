<?php

namespace App\Models\Masterdata;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Masterdata\Jabatan;
use App\Models\Transaksi\Presensi;
use App\Models\Masterdata\Perusahaan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $primaryKey = 'id_karyawan';

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_karyawan)) {
                $model->id_karyawan = Str::uuid();
            }
        });
    }

    protected $fillable = [
        'nama', 'no_telp', 'jenis_kelamin', 'email', 'alamat', 'status', 'id_jabatan', 'id_perusahaan', 'id_user','nik'
    ];

    public $timestamps = true;

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'id_karyawan');
    }
}
