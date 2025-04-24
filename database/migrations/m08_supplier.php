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
        // Create supplier table
        Schema::create('supplier', function (Blueprint $table) {
            $table->uuid('id_supplier')->primary();
            $table->string('nama', 50);
            $table->string('alamat', 50);
            $table->string('no_telp', 50);
            $table->string('status')->default('Aktif');
            $table->timestamps();
            
            $table->foreignId('id_perusahaan');
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('perusahaan')->onDelete('cascade');
        });

        // Create pivot table for many-to-many relationship between supplier and produk
        Schema::create('produk_supplier', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_produk');
            $table->uuid('id_supplier');
            $table->timestamps();

            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
            $table->foreign('id_supplier')->references('id_supplier')->on('supplier')->onDelete('cascade');

            // Ensure that each combination of product and supplier is unique
            $table->unique(['id_produk', 'id_supplier']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_supplier');
        Schema::dropIfExists('supplier');
    }
};