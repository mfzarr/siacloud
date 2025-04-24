<?php

namespace App\Models\Masterdata;

use Illuminate\Support\Str;
use App\Models\Masterdata\Perusahaan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts';

    protected $primaryKey = 'id_discount';

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_discount)) {
                $model->id_discount = Str::uuid();
            }
        });
    }

    protected $fillable = [
        'min_transaksi',
        'discount_percentage',
        'id_perusahaan',
    ];
    
    public $timestamps = true; // Auto update created_at and updated_at

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }
}
