<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggajian', function (Blueprint $table) {
            $table->uuid('id_gaji')->primary();
            $table->string('no_transaksi_gaji')->unique();
            $table->date('tanggal_penggajian');
            
            $table->uuid('id_karyawan')->nullable();
            $table->foreign('id_karyawan')
                ->references('id_karyawan')
                ->on('karyawan')
                ->nullOnDelete();
            
            $table->unsignedInteger('tarif'); // Retrieved from jabatan
            $table->decimal('bonus', 5, 2); // Bonus percentage
            $table->unsignedInteger('total_service'); // Sum from penjualan_detail
            $table->unsignedInteger('bonus_service'); // Calculated from bonus * total_service
            $table->unsignedInteger('total_kehadiran'); // Count from presensi
            $table->unsignedInteger('bonus_kehadiran'); // Additional bonus per attendance
            $table->unsignedInteger('total_bonus_kehadiran'); // total_kehadiran * bonus_kehadiran
            $table->unsignedInteger('tunjangan_makan');
            $table->unsignedInteger('tunjangan_jabatan');
            $table->unsignedInteger('lembur');
            $table->unsignedInteger('potongan_gaji')->nullable();
            $table->string('detail_potongan')->nullable();
            $table->unsignedInteger('total_gaji_bersih'); // Calculated final salary
            
            // Foreign key to perusahaan
            $table->unsignedBigInteger('id_perusahaan');
            $table->foreign('id_perusahaan')
                ->references('id_perusahaan')
                ->on('perusahaan')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};
