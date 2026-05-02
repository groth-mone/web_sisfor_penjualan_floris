<?php
// database/migrations/2026_05_02_000007_create_barang_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id_barang');
            $table->foreignId('id_kategori')->nullable()->constrained('kategori', 'id_kategori')->onDelete('set null')->onUpdate('set null');
            $table->string('kode_barang', 200);
            $table->string('nama_barang', 250);
            $table->string('satuan_barang', 150);
            $table->string('gambar', 255)->nullable();
            $table->integer('stok_minimum')->default(5);
            $table->decimal('harga_default', 15, 2)->default(0);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang');
    }
};