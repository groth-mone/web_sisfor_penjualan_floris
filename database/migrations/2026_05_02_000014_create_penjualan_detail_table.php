<?php
// database/migrations/2026_05_02_000014_create_penjualan_detail_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->id('id_penjualan_detail');
            $table->foreignId('id_penjualan')->constrained('penjualan', 'id_penjualan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_barang')->nullable()->constrained('barang', 'id_barang')->onDelete('set null')->onUpdate('set null');
            $table->foreignId('id_produk')->nullable()->constrained('produk', 'id_produk');
            $table->foreignId('id_pembelian_detail')->nullable()->constrained('pembelian_detail', 'id_pembelian_detail');
            $table->string('harga_penjualan', 50)->nullable();
            $table->integer('jml_penjualan');
            $table->integer('qty_produk_jadi_terpakai')->default(0);
            $table->integer('qty_racik')->default(0);
            $table->text('catatan_detail')->nullable();
            
            $table->index('id_penjualan');
            $table->index('id_barang');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualan_detail');
    }
};