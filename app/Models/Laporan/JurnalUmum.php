<?php

namespace App\Models\Laporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Masterdata\Coa; // Update the namespace if Coa is in a different location
use App\Models\Masterdata\Perusahaan; // Add this line to import the Perusahaan class

class JurnalUmum extends Model
{
    use HasFactory;

    protected $table = 'jurnal_umum';

    protected $fillable = [
        'id_coa',
        'tanggal_jurnal',
        'nama_akun',
        'kode_akun',
        'debit',
        'credit',
        'id_perusahaan',
        'transaction_id', // Add transaction_id to fillable
    ];

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'id_coa');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    public static function createFromTransaction($transactionData, $perusahaanId)
    {
        foreach ($transactionData['entries'] as $entry) {
            self::create([
                'id_coa' => $entry['id_coa'],
                'tanggal_jurnal' => $entry['tanggal_jurnal'], // Use current date if not provided
                'nama_akun' => $entry['nama_akun'],
                'kode_akun' => $entry['kode_akun'],
                'debit' => $entry['debit'] ?? null,
                'credit' => $entry['credit'] ?? null,
                'id_perusahaan' => $perusahaanId,
                'transaction_id' => $entry['transaction_id'] ?? null, // Make transaction_id optional
            ]);
        }
    }
}
