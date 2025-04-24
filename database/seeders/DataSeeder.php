<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed perusahaan table
        DB::table('perusahaan')->insert([
            'id_perusahaan' => 1,
            'nama' => 'Perusahaan Dagang 1',
            'alamat' => 'bojongsoang',
            'jenis_perusahaan' => 'dagang',
            'kode_perusahaan' => 'PD001', // Assuming you want a unique code
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Seed users table
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'email' => 'admin1@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('Admin123'),
                'role' => 'owner',
                'status' => 'aktif',
                'id_perusahaan' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'karyawan',
                'email' => 'karyawan1@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('Admin123'),
                'role' => 'pegawai',
                'status' => 'aktif',
                'id_perusahaan' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}