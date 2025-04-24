<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jurnal_umum', function (Blueprint $table) {
            $table->id('id_jurnal');

            // Foreign key coa
            $table->uuid('id_coa');
            $table->foreign('id_coa')->references('id_coa')->on('coa');

            $table->date('tanggal_jurnal');
            // Account Code and Name
            $table->string('nama_akun');
            $table->string('kode_akun');

            // Debit and Credit amounts (nullable)
            $table->decimal('debit', 15, 2)->nullable();
            $table->decimal('credit', 15, 2)->nullable();

            // Transaction ID (to correlate entries)
            $table->uuid('transaction_id')->nullable();  // Or unsignedBigInteger if you prefer

            // Foreign Key for Company (assuming you have a perusahaan table)
            $table->unsignedBigInteger('id_perusahaan');
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('perusahaan');

            // Timestamps
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jurnal_umum');
    }
};
