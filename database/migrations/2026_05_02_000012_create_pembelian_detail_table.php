<?php
// database/migrations/2026_05_02_000012_create_pembelian_detail_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembelian_detail', function (Blueprint $table) {
            $table->id('id_pembelian_detail');
            $table->foreignId('id_pembelian')->constrained('pembelian', 'id_pembelian')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_barang')->nullable()->constrained('barang', 'id_barang')->onDelete('set null')->onUpdate('set null');
            $table->string('harga_beli', 150);
            $table->string('harga_jual', 150);
            $table->integer('jml_pembelian');
            $table->date('tanggal_exp')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembelian_detail');
    }
};