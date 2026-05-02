<?php
// database/migrations/2026_05_02_000010_create_detail_produk_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_produk', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk');
            $table->foreignId('id_barang')->constrained('barang', 'id_barang');
            $table->decimal('jumlah_pakai', 12, 2)->default(0);
            $table->timestamps();
            
            $table->index('id_produk');
            $table->index('id_barang');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_produk');
    }
};