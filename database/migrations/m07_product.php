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
        Schema::create('produk', function (Blueprint $table) {
            $table->uuid('id_produk')->primary();
            $table->string('nama');
            $table->uuid('id_kategori_barang');
            $table->integer('stok');
            $table->BigInteger('harga');
            $table->BigInteger('hpp');
            $table->string('status')->default('Aktif');
            $table->unsignedBigInteger('id_perusahaan');
            $table->timestamps();

            // Foreign key ke tabel 'kategori_barang'
            $table->foreign('id_kategori_barang')
                  ->references('id_kategori_barang')
                  ->on('kategori_barang')
                  ->onDelete('cascade');

            // Foreign key ke tabel 'perusahaan'
            $table->foreign('id_perusahaan')
                  ->references('id_perusahaan')
                  ->on('perusahaan')
                  ->onDelete('cascade');

            // Foreign key ke kolom 'kelompok_akun' di tabel 'coa_kelompok'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
