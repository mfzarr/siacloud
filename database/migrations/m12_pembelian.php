<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create pembelian table
        Schema::create('pembelian', function (Blueprint $table) {
            $table->uuid('id_pembelian')->primary(); // Primary key
            $table->string('no_transaksi_pembelian')->unique();
            $table->date('tanggal_pembelian');

            $table->uuid('supplier');
            $table->foreign('supplier')
                ->references('id_supplier')
                ->on('supplier')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_perusahaan');
            $table->foreign('id_perusahaan')
                ->references('id_perusahaan')
                ->on('perusahaan')
                ->onDelete('cascade');

            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('total_dibayar', 15, 2)->default(0);
            $table->string('tipe_pembayaran')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        // Create pembelian_detail table untuk mysql
        // Schema::create('pembelian_detail', function (Blueprint $table) {
        //     $table->id('id_pembelian_detail');

        //     // Add id_pembelian as foreign key
        //     $table->unsignedBigInteger('id_pembelian');
        //     $table->foreign('id_pembelian')
        //         ->references('id_pembelian')
        //         ->on('pembelian')
        //         ->onDelete('cascade');

        //     // Add id_produk as foreign key
        //     $table->unsignedBigInteger('id_produk');
        //     $table->foreign('id_produk')
        //         ->references('id_produk')
        //         ->on('produk')
        //         ->onDelete('cascade');

        //     $table->integer('qty');
        //     $table->decimal('harga', 15, 2);
        //     $table->decimal('sub_total_harga', 15, 2)->virtualAs('qty * harga');
        //     $table->decimal('dibayar', 15, 2)->default(0);
        //     $table->timestamps();
        // });

        // Create pembelian_detail table untuk pgsql
        // Create pembelian_detail table for PostgreSQL
        // Create pembelian_detail table for PostgreSQL
        Schema::create('pembelian_detail', function (Blueprint $table) {
            $table->uuid('id_pembelian_detail')->primary(); // Primary key

            // Add id_pembelian as foreign key
            $table->uuid('id_pembelian');
            $table->foreign('id_pembelian')
                ->references('id_pembelian')
                ->on('pembelian')
                ->onDelete('cascade');

            // Add id_produk as foreign key
            $table->uuid('id_produk');
            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->onDelete('cascade');

            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('dibayar', 15, 2)->default(0);
            $table->timestamps();
        });

        // Add the generated column after table creation
        DB::statement('ALTER TABLE pembelian_detail ADD COLUMN sub_total_harga DECIMAL(15,2) GENERATED ALWAYS AS (qty * harga) STORED');
        // Create pelunasan_pembelian table
        Schema::create('pelunasan_pembelian', function (Blueprint $table) {
            $table->uuid('id_pelunasan')->primary(); // Primary key

            $table->uuid('id_pembelian');
            $table->foreign('id_pembelian')
                ->references('id_pembelian')
                ->on('pembelian')
                ->onDelete('cascade');

            $table->uuid('id_produk');
            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->onDelete('cascade');


            $table->decimal('total_pelunasan', 15, 2);
            $table->date('tanggal_pelunasan');
            $table->timestamps();
        });

        // Trigger to update total_dibayar on pembelian table
        // Trigger function
        DB::unprepared('
    CREATE TRIGGER after_pelunasan_insert
    AFTER INSERT ON pelunasan_pembelian
    FOR EACH ROW
    BEGIN
        UPDATE pembelian
        SET total_dibayar = (
            SELECT COALESCE(SUM(total_pelunasan), 0)
            FROM pelunasan_pembelian
            WHERE id_pembelian = NEW.id_pembelian
        )
        WHERE id_pembelian = NEW.id_pembelian;

        UPDATE pembelian
        SET status = CASE
            WHEN total <= total_dibayar THEN \'Lunas\'
            ELSE \'Belum Lunas\'
        END
        WHERE id_pembelian = NEW.id_pembelian;

        -- RETURN NEW;
    END;
');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_pelunasan_insert ON pelunasan_pembelian');
        DB::unprepared('DROP FUNCTION IF EXISTS update_pembelian_after_pelunasan');
        Schema::dropIfExists('pelunasan_pembelian');
        Schema::dropIfExists('pembelian_detail');
        Schema::dropIfExists('pembelian');
    }
};
