<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->uuid('id_discount')->primary();
            $table->integer('min_transaksi')->unique(); // Minimum transactions
            $table->integer('discount_percentage');    // Discount in percentage
            $table->foreignId('id_perusahaan'); // Foreign key column
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('perusahaan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
