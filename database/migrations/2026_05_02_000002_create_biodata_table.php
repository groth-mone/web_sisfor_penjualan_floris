<?php
// database/migrations/2026_05_02_000002_create_biodata_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('biodata', function (Blueprint $table) {
            $table->id('id_biodata');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('telepon', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto', 250)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('biodata');
    }
};