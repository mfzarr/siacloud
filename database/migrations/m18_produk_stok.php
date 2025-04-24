<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produk_stok', function (Blueprint $table) {
            $table->uuid('id_stok_produk')->primary();
            $table->uuid('id_produk');
            $table->uuid('id_penjualan_detail')->nullable();
            $table->uuid('id_pembelian_detail')->nullable();
            $table->integer('jumlah');
            $table->enum('jenis', ['penjualan', 'pembelian']);
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->timestamps();

            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
            $table->foreign('id_penjualan_detail')->references('id_penjualan_detail')->on('penjualan_detail')->onDelete('set null');
            $table->foreign('id_pembelian_detail')->references('id_pembelian_detail')->on('pembelian_detail')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk_stok');
    }
};