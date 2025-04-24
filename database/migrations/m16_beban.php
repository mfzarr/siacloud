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
        Schema::create('pengeluaran_beban', function (Blueprint $table) {
            $table->uuid('id_beban')->primary(); // Primary key
            $table->string('nama_beban'); // Name of the expense
            $table->integer('harga'); // Cost of the expense
            $table->date('tanggal'); // Date of the expense
            $table->string('status')->default('Pending'); // Status of the expense, default to 'Pending'

            // Foreign key to perusahaan
            $table->unsignedBigInteger('id_perusahaan');
            $table->foreign('id_perusahaan')
                ->references('id_perusahaan')
                ->on('perusahaan')
                ->onDelete('cascade');

            // Foreign key to coa
            $table->uuid('id_coa');
            $table->foreign('id_coa')
                ->references('id_coa')
                ->on('coa')
                ->onDelete('cascade');

            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_beban');
    }
};
