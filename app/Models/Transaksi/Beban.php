<?php

namespace App\Models\Transaksi;

use Illuminate\Support\Str;
use App\Models\Masterdata\Coa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Beban extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_beban';

    protected $primaryKey = 'id_beban'; // Specify the primary key

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_beban)) {
                $model->id_beban = Str::uuid();
            }
        });
    }

    protected $fillable = [
        'nama_beban',
        'harga',
        'tanggal',
        'id_perusahaan',
        'id_coa',
    ];

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'id_coa', 'id_coa');
    }
}

