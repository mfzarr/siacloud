<?php

namespace App\Models\Masterdata;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'id_jabatan';
    public $timestamps = true;


    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_jabatan)) {
                $model->id_jabatan = Str::uuid();
            }
        });
    }

    protected $fillable = [
        'nama', 'asuransi', 'tarif', 'tarif_tidak_tetap', 'id_perusahaan'
    ];
}

