<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->uuid('id_penjualan')->primary(); // Primary key

            $table->string('no_transaksi_penjualan')->unique();

            // Foreign key to users (who input the data)
            $table->foreignId('id')->constrained('users')->onDelete('cascade');

            // Foreign key to pelanggan
            $table->uuid('id_pelanggan')->nullable();
            $table->foreign('id_pelanggan')
                ->references('id_pelanggan')
                ->on('pelanggan')
                ->cascadeOnDelete();

            $table->date('tgl_transaksi');
            $table->integer('total');
            $table->decimal('hpp', 15, 2);
            $table->integer('discount')->default(0);
            $table->string('status');

            // Foreign key to perusahaan
            $table->unsignedBigInteger('id_perusahaan');
            $table->foreign('id_perusahaan')
                ->references('id_perusahaan')
                ->on('perusahaan')
                ->onDelete('cascade');
            
            
            $table->timestamps();
        });

        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->uuid('id_penjualan_detail')->primary(); // Primary key
        
            $table->uuid('id_penjualan');
            $table->foreign('id_penjualan')
                ->references('id_penjualan')
                ->on('penjualan')
                ->onDelete('cascade');
        
            $table->uuid('id_produk');
            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->onDelete('cascade');
        
            $table->integer('harga');
            $table->integer('kuantitas');
        
            $table->uuid('id_pegawai')->nullable();
            $table->foreign('id_pegawai')
                ->references('id_karyawan')
                ->on('karyawan')
                ->nullOnDelete();
        
            $table->timestamps();
        });
        
        // Add the generated column after table creation
        DB::statement('ALTER TABLE penjualan_detail ADD COLUMN total INTEGER GENERATED ALWAYS AS (harga * kuantitas) STORED');
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_detail');
        Schema::dropIfExists('penjualan');
    }
};
