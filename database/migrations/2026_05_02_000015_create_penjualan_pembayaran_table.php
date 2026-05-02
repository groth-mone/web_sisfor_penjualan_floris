<?php
// database/migrations/2026_05_02_000015_create_penjualan_pembayaran_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penjualan_pembayaran', function (Blueprint $table) {
            $table->id('id_penjualan_pembayaran');
            $table->foreignId('id_penjualan')->constrained('penjualan', 'id_penjualan')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('metode_pembayaran', ['Tunai', 'Transfer'])->nullable();
            $table->string('metode_detail', 150)->nullable();
            $table->string('nominal_pembayaran', 50)->nullable();
            $table->date('tanggal_pembayaran')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualan_pembayaran');
    }
};