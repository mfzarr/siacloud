<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_produk', function (Blueprint $table) {
            $table->uuid('id_stok_produk')->primary();
            $table->date('bulan');
            $table->uuid('id_produk');
            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
            $table->integer('stok_awal');
            $table->integer('stok_masuk');
            $table->integer('stok_keluar');
            $table->integer('stok_akhir');
            $table->unsignedBigInteger('id_perusahaan');
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('perusahaan');
            $table->timestamps();

            $table->unique(['id_produk', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_produk');
    }
};