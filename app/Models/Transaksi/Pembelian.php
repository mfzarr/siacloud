<?php

namespace App\Models\Transaksi;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Laporan\RekapHutang;
use App\Models\Masterdata\Supplier;
use App\Models\laporan\GeneralLedger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'id_pembelian';
    protected $guarded = [];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_pembelian)) {
                $model->id_pembelian = Str::uuid();
            }

            $date = Carbon::now()->format('Ymd');

            // Set default id to 1 if no record exists yet
            $lastTransaction = self::whereDate('created_at', Carbon::today())->orderBy('no_transaksi_pembelian', 'desc')->first();
            $nextId = $lastTransaction ? intval(substr($lastTransaction->no_transaksi_pembelian, -4)) + 1 : 1;
            $formattedId = str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $model->no_transaksi_pembelian = "PBL/{$date}/{$formattedId}";
        });
    }

    // In Pembelian.php model
    public function supplierRelation()
    {
        return $this->belongsTo(Supplier::class, 'supplier', 'id_supplier');
    }

    public function pelunasanPembelian()
    {
        return $this->hasMany(PelunasanPembelian::class, 'id_pembelian');
    }

    public function pembelianDetails()
    {
        return $this->hasMany(Pembeliandetail::class, 'id_pembelian', 'id_pembelian');
    }
    public function getRemainingPaymentAttribute()
    {
        return $this->total - $this->total_dibayar;
    }
    public function rekap()
    {
        return $this->hasOne(RekapHutang::class, 'pembelian_id', 'id_pembelian');
    }
}
