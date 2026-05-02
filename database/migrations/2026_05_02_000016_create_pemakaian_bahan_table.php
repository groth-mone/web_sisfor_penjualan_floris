<?php
// database/migrations/2026_05_02_000016_create_pemakaian_bahan_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemakaian_bahan', function (Blueprint $table) {
            $table->id('id_pemakaian_bahan');
            $table->foreignId('id_penjualan_detail')->constrained('penjualan_detail', 'id_penjualan_detail');
            $table->foreignId('id_barang')->constrained('barang', 'id_barang');
            $table->decimal('qty_pakai', 12, 2)->default(0);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
            
            $table->index('id_penjualan_detail');
            $table->index('id_barang');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemakaian_bahan');
    }
};