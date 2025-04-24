<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->uuid('id_pelanggan')->primary();
            $table->string('nama');
            $table->string('email')->unique()->nullable();
            $table->string('no_telp');
            $table->string('alamat');
            $table->string('jenis_kelamin');
            $table->date('tgl_daftar');
            $table->integer('jumlah_transaksi')->default(0); // New column for tracking transaction count
            $table->string('status')->default('Aktif');

            $table->foreignId('id_perusahaan');
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('perusahaan')->onDelete('cascade');

            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
