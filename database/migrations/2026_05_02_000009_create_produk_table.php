<?php
// database/migrations/2026_05_02_000009_create_produk_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('kode_produk', 100)->unique();
            $table->string('nama_produk', 200);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->integer('stok_produk_jadi')->default(0);
            $table->string('gambar', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk');
    }
};