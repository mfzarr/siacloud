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
        Schema::create('jabatan', function (Blueprint $table) {
            $table->uuid('id_jabatan')->primary();
            $table->string('nama');
            $table->bigInteger('asuransi')->nullable();
            $table->bigInteger('tarif')->nullable();
            // $table->integer('tarif_tidak_tetap')->nullable();   
            $table->string('status')->default('Aktif');
            $table->foreignId('id_perusahaan'); // Foreign key column
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('perusahaan')->onDelete('cascade');
            
            $table->timestamps();
        });
    }
        


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan');
    }
};
