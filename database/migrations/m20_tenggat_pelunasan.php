<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rekap_hutang', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('pembelian_id')->unique();
            $table->uuid('id_supplier');
            $table->decimal('total_hutang', 15, 2);
            $table->decimal('total_dibayar', 15, 2)->default(0);
            $table->decimal('sisa_hutang', 15, 2);
            $table->date('tenggat_pelunasan')->nullable();
            $table->timestamps();

            $table->foreign('pembelian_id')->references('id_pembelian')->on('pembelian')->onDelete('cascade');
            $table->foreign('id_supplier')->references('id_supplier')->on('supplier')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekap_hutang');
    }
};

