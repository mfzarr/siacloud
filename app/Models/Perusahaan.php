<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';
    protected $fillable = ['nama', 'alamat', 'jenis_perusahaan', 'kode_perusahaan'];
    protected $primaryKey = 'id_perusahaan';

    // Event to handle what happens after a Perusahaan is created
    protected static function booted()
    {
        static::created(function ($perusahaan) {
            // Automatically create COA data for the new Perusahaan
            $perusahaan->createCoaForPerusahaan();
        });
    }

    /**
     * Create default COA entries linked to the newly created Perusahaan.
     */
public function createCoaForPerusahaan()
{
    // Create COA groups
    DB::table('coa_kelompok')->insert([
        ['kelompok_akun' => '1', 'nama_kelompok_akun' => 'Aset', 'id_perusahaan' => $this->id_perusahaan, 'created_at' => now(), 'updated_at' => now()],
        ['kelompok_akun' => '2', 'nama_kelompok_akun' => 'Kewajiban', 'id_perusahaan' => $this->id_perusahaan, 'created_at' => now(), 'updated_at' => now()],
        ['kelompok_akun' => '3', 'nama_kelompok_akun' => 'Ekuitas', 'id_perusahaan' => $this->id_perusahaan, 'created_at' => now(), 'updated_at' => now()],
        ['kelompok_akun' => '4', 'nama_kelompok_akun' => 'Penjualan', 'id_perusahaan' => $this->id_perusahaan, 'created_at' => now(), 'updated_at' => now()],
        ['kelompok_akun' => '5', 'nama_kelompok_akun' => 'Pembelian dan Beban', 'id_perusahaan' => $this->id_perusahaan, 'created_at' => now(), 'updated_at' => now()],
    ]);

    // Base COA accounts
    $baseCoaAccounts = [
        ['kode_akun' => '1101', 'nama_akun' => 'Kas', 'kelompok_akun' => '1', 'posisi_d_c' => 'Debit'],
        ['kode_akun' => '1102', 'nama_akun' => 'Piutang Dagang', 'kelompok_akun' => '1', 'posisi_d_c' => 'Debit'],
        ['kode_akun' => '1103', 'nama_akun' => 'Persediaan Barang Dagang', 'kelompok_akun' => '1', 'posisi_d_c' => 'Debit'],
        ['kode_akun' => '1104', 'nama_akun' => 'Perlengkapan', 'kelompok_akun' => '1', 'posisi_d_c' => 'Debit'],
        ['kode_akun' => '1201', 'nama_akun' => 'Peralatan', 'kelompok_akun' => '1', 'posisi_d_c' => 'Debit'],
        ['kode_akun' => '1202', 'nama_akun' => 'Akumulasi Penyusutan Peralatan', 'kelompok_akun' => '1', 'posisi_d_c' => 'Kredit'],
        ['kode_akun' => '2101', 'nama_akun' => 'Utang Dagang', 'kelompok_akun' => '2', 'posisi_d_c' => 'Kredit'],
        ['kode_akun' => '3101', 'nama_akun' => 'Modal', 'kelompok_akun' => '3', 'posisi_d_c' => 'Kredit'],
        ['kode_akun' => '4101', 'nama_akun' => 'Penjualan', 'kelompok_akun' => '4', 'posisi_d_c' => 'Kredit'],
        ['kode_akun' => '5101', 'nama_akun' => 'Harga Pokok Penjualan', 'kelompok_akun' => '5', 'posisi_d_c' => 'Debit'],
        ['kode_akun' => '5201', 'nama_akun' => 'Beban Gaji', 'kelompok_akun' => '5', 'posisi_d_c' => 'Debit'],
    ];

    // Generate COA data with different dates
    $coaData = [];
    $now = Carbon::now();
    
    // Create 11 sets of COA data with varying dates
    for ($i = -5; $i <= 5; $i++) {
        $date = $now->copy()->addMonths($i)->startOfMonth()->toDateString();
        
        foreach ($baseCoaAccounts as $account) {
            $coaData[] = [
                'id_coa' => Str::uuid(),
                'kode_akun' => $account['kode_akun'],
                'nama_akun' => $account['nama_akun'],
                'kelompok_akun' => $account['kelompok_akun'],
                'posisi_d_c' => $account['posisi_d_c'],
                'saldo_awal' => 0,
                'tanggal_saldo_awal' => $date,
                'status' => 'seeder',
                'id_perusahaan' => $this->id_perusahaan,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
    }

    // Insert all COA data
    DB::table('coa')->insert($coaData);
}
    // Define a one-to-many relationship with users
    public function users()
    {
        return $this->hasMany(User::class, 'id_perusahaan');
    }

    // Define the owner relationship (only fetch the user with the 'owner' role)
    public function owner()
    {
        return $this->hasOne(User::class, 'id_perusahaan')->where('role', 'owner');
    }
}
