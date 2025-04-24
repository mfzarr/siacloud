<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrasi untuk tabel perusahaan
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id('id_perusahaan');
            $table->string('nama');
            $table->string('alamat');
            $table->string('jenis_perusahaan');
            $table->string('kode_perusahaan', 9)->unique(); // 9-character unique code
            $table->timestamps();
        });


        // Tabel users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->nullable();
            $table->string('google_token')->nullable();
            $table->string('google_refresh_token')->nullable();
            $table->string('name', 50)->unique();
            $table->string('email', 50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 50)->nullable(); 
            $table->string('status', 50)->default('aktif')->nullable();

            // Foreign key must reference the correct type and column
            $table->unsignedBigInteger('id_perusahaan')->nullable(); // Ensure it's nullable
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('perusahaan')->onDelete('set null');
        
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('perusahaan');
    }
};
