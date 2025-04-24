<?php

namespace App\Models\Masterdata;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';
    protected $primaryKey = 'id_assets';
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_assets)) {
                $model->id_assets = Str::uuid();
            }
        });
    }

    protected $fillable = [
        'nama_asset',
        'harga_perolehan',
        'nilai_sisa',
        'masa_manfaat',
        'id_perusahaan',
        'tanggal_perolehan',
    ];

    protected $casts = [
        'harga_perolehan' => 'float',
        'nilai_sisa' => 'float',
        'masa_manfaat' => 'integer',
        'tanggal_perolehan' => 'date',
    ];

    public function getDepreciationPerMonthAttribute()
    {
        if ($this->masa_manfaat > 0) {
            return ($this->harga_perolehan - $this->nilai_sisa) / ($this->masa_manfaat * 12);
        }
        return 0;
    }

    public function calculateDepreciationSchedule()
    {
        $penyusutan_per_bulan = $this->depreciation_per_month;
        $akumulasi_penyusutan = 0;
        $nilai_buku = $this->harga_perolehan;
    
        $schedule = [];
        $start_date = new \DateTime($this->tanggal_perolehan);
    
        for ($month = 0; $month < $this->masa_manfaat * 12; $month++) {
            $current_date = (clone $start_date)->modify("+$month months");
            $month_year = $current_date->format('F Y');
            $month_number = (int) $current_date->format('n');
    
            $akumulasi_penyusutan += $penyusutan_per_bulan;
            $nilai_buku -= $penyusutan_per_bulan;
    
            $schedule[] = [
                'bulan_tahun' => $month_year,
                'bulan' => $month_number,
                'biaya_penyusutan' => $penyusutan_per_bulan,
                'akumulasi_penyusutan' => $akumulasi_penyusutan,
                'nilai_buku' => max($nilai_buku, $this->nilai_sisa),
            ];
    
            if ($nilai_buku <= $this->nilai_sisa) {
                break;
            }
        }
    
        return $schedule;
    }
}