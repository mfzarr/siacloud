<?php

namespace App\Models\Masterdata;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CoaKelompok extends Model
{
    protected $table = 'coa_kelompok'; // Table name
    
    protected $fillable = [
        'kelompok_akun',
        'nama_kelompok_akun',
        'header_akun',
        'id_perusahaan',
    ];

    public $timestamps = false; // Assuming the table doesn't have created_at or updated_at columns
}
