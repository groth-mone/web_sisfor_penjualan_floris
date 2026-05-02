<?php
// database/migrations/2026_05_02_000013_create_penjualan_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->string('pelanggan', 150)->nullable();
            $table->string('nomor_pelanggan', 50)->nullable();
            $table->string('kode_penjualan', 50);
            $table->date('tanggal_penjualan');
            $table->text('keterangan_penjualan')->nullable();
            $table->decimal('ongkir', 15, 2)->default(0);
            $table->text('alamat_pengiriman')->nullable();
            $table->enum('status_pengiriman', ['Pesanan Masuk', 'Diproses', 'Dikirim', 'Selesai'])->default('Pesanan Masuk');
            $table->timestamp('waktu_pesanan_masuk')->nullable();
            $table->timestamp('waktu_diproses')->nullable();
            $table->timestamp('waktu_dikirim')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->string('foto_pesanan', 255)->nullable();
            $table->text('catatan_kuitansi')->nullable();
            $table->enum('status_penjualan', ['Proses', 'Selesai']);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualan');
    }
};