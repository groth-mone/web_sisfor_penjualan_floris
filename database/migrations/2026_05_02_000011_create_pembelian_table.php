<?php
// database/migrations/2026_05_02_000011_create_pembelian_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id('id_pembelian');
            $table->foreignId('id_supplier')->nullable()->constrained('supplier', 'id_supplier')->onDelete('set null')->onUpdate('set null');
            $table->string('kode_pembelian', 50);
            $table->date('tanggal_pembelian');
            $table->enum('status_pembelian', ['Display', 'Gudang']);
            $table->text('keterangan_pembelian')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembelian');
    }
};