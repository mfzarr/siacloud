<?php

namespace App\Models\Masterdata;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang1 extends Model
{
    use HasFactory;

    protected $table = 'barang1'; // maps to 'barang1' table

    protected $primaryKey = 'id_barang1'; // set the primary key

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_barang1)) {
                $model->id_barang1 = Str::uuid();
            }
        });
    }

    protected $fillable = [
        'nama',
        // 'detail',
        // 'satuan',
        'kategori',
        'id_perusahaan',
    ]; // mass-assignable attributes

    public $timestamps = true; // if you are using created_at and updated_at columns

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }
}
