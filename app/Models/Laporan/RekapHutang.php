<?php
namespace App\Models\Laporan;

use Illuminate\Support\Str;
use App\Models\Masterdata\Supplier;
use App\Models\Transaksi\Pembelian;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi\PelunasanPembelian;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekapHutang extends Model
{
    use HasFactory;

    protected $table = 'rekap_hutang';
    protected $dates = ['tenggat_pelunasan'];
    protected $primaryKey = 'uuid'; // Primary key set to 'id' as in the migration

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(callback: function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    protected $fillable = [
        'pembelian_id',
        'id_supplier',
        'total_hutang',
        'total_dibayar',
        'sisa_hutang',
        'tenggat_pelunasan'
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
    public function pelunasanPembelian()
    {
        return $this->hasMany(PelunasanPembelian::class, 'id_pembelian');
    }
}
