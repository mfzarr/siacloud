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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->uuid('id_karyawan')->primary();
            $table->string('nama');
            $table->string('no_telp');
            $table->string('jenis_kelamin');
            $table->string('email')->nullable();
            $table->string('alamat');
            $table->string('status')->default('Aktif');
            $table->string('nik');
            $table->uuid('id_jabatan');
            $table->foreignId('id_perusahaan');
            $table->foreignId('id_user')->nullable(); // Make id_user nullable
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('id_jabatan')->references('id_jabatan')->on('jabatan')->onDelete('cascade');
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('perusahaan')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};

