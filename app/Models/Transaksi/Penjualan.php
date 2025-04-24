<?php

namespace App\Models\Transaksi;

use Carbon\Carbon;
use Illuminate\Support\Str;
use app\Models\Masterdata\Coa;
use App\Models\Masterdata\User;
use App\Models\Masterdata\Karyawan;
use App\Models\Masterdata\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi\PenjualanDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $guarded = [];

    /**
     * Boot the model to auto-generate `no_transaksi_penjualan`.
     */
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($penjualan) {
            if (empty($penjualan->id_penjualan)) {
                $penjualan->id_penjualan = Str::uuid();
            }

            $date = Carbon::now()->format('Ymd');

            // Set default id to 1 if no record exists yet
            $lastTransaction = self::whereDate('created_at', Carbon::today())->orderBy('no_transaksi_penjualan', 'desc')->first();
            $nextId = $lastTransaction ? intval(substr($lastTransaction->no_transaksi_penjualan, -4)) + 1 : 1;
            $formattedId = str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $penjualan->no_transaksi_penjualan = "PJL/{$date}/{$formattedId}";
        });
    }

    /**
     * Relationship with PenjualanDetail.
     */
    public function penjualanDetails()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan', 'id_penjualan');
    }

    /**
     * Relationship with Pelanggan (Customer).
     */
    public function pelangganRelation()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Relationship with Karyawan (Employee).
     */
    public function pegawaiRelation()
    {
        return $this->belongsTo(Karyawan::class, 'id_pegawai', 'id_karyawan');
    }

    public function userRelation()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'id_perusahaan', 'id_perusahaan');
    }
}
