<?php

namespace App\Models\Masterdata;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan'; // maps to 'pelanggan' table

    protected $primaryKey = 'id_pelanggan'; // set the primary key

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_pelanggan)) {
                $model->id_pelanggan = Str::uuid();
            }
        });
    }
    protected $fillable = [
        'nama',
        'email',
        'no_telp',
        'alamat',
        'jenis_kelamin',
        'tgl_daftar',
        'status',
        'id_perusahaan',
    ]; // mass-assignable attributes

    public $timestamps = true; // if you are using created_at and updated_at columns

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }
}
