<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------
        // 1. biodata (depends on: users)
        // -------------------------------------------------------
        Schema::create('biodata', function (Blueprint $table) {
            $table->increments('id_biodata');
            $table->unsignedInteger('id_user')->unique();
            $table->string('telepon', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto', 250)->nullable();

            $table->foreign('id_user')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        // -------------------------------------------------------
        // 2. kategori
        // -------------------------------------------------------
        Schema::create('kategori', function (Blueprint $table) {
            $table->increments('id_kategori');
            $table->string('nama_kategori', 250);
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });

        // -------------------------------------------------------
        // 3. supplier
        // -------------------------------------------------------
        Schema::create('supplier', function (Blueprint $table) {
            $table->increments('id_supplier');
            $table->string('nama_supplier', 250);
            $table->string('telepon_supplier', 150);
            $table->text('alamat_supplier');
            $table->timestamps();
        });

        // -------------------------------------------------------
        // 4. barang (depends on: kategori)
        // -------------------------------------------------------
        Schema::create('barang', function (Blueprint $table) {
            $table->increments('id_barang');
            $table->unsignedInteger('id_kategori')->nullable();
            $table->string('kode_barang', 200);
            $table->string('nama_barang', 250);
            $table->string('satuan_barang', 150);
            $table->string('gambar', 255)->nullable();
            $table->integer('stok_minimum')->default(5);
            $table->decimal('harga_default', 15, 2)->default(0.00);
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->foreign('id_kategori')
                  ->references('id_kategori')->on('kategori')
                  ->onDelete('set null')
                  ->onUpdate('set null');
        });

        // -------------------------------------------------------
        // 5. produk
        // -------------------------------------------------------
        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->string('kode_produk', 100)->unique();
            $table->string('nama_produk', 200);
            $table->decimal('harga_jual', 15, 2)->default(0.00);
            $table->integer('stok_produk_jadi')->default(0);
            $table->string('gambar', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });

        // -------------------------------------------------------
        // 6. detail_produk (depends on: produk, barang)
        // -------------------------------------------------------
        Schema::create('detail_produk', function (Blueprint $table) {
            $table->increments('id_detail');
            $table->integer('id_produk');
            $table->integer('id_barang');
            $table->decimal('jumlah_pakai', 12, 2)->default(0.00);
            $table->timestamps();

            $table->index('id_produk', 'detail_produk_id_produk_index');
            $table->index('id_barang', 'detail_produk_id_barang_index');
        });

        // -------------------------------------------------------
        // 7. pembelian (depends on: supplier)
        // -------------------------------------------------------
        Schema::create('pembelian', function (Blueprint $table) {
            $table->increments('id_pembelian');
            $table->unsignedInteger('id_supplier')->nullable();
            $table->string('kode_pembelian', 50);
            $table->date('tanggal_pembelian');
            $table->enum('status_pembelian', ['Display', 'Gudang']);
            $table->text('keterangan_pembelian')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->foreign('id_supplier')
                  ->references('id_supplier')->on('supplier')
                  ->onDelete('set null')
                  ->onUpdate('set null');
        });

        // -------------------------------------------------------
        // 8. pembelian_detail (depends on: pembelian, barang)
        // -------------------------------------------------------
        Schema::create('pembelian_detail', function (Blueprint $table) {
            $table->increments('id_pembelian_detail');
            $table->unsignedInteger('id_pembelian');
            $table->unsignedInteger('id_barang')->nullable();
            $table->string('harga_beli', 150);
            $table->string('harga_jual', 150);
            $table->integer('jml_pembelian');
            $table->date('tanggal_exp')->nullable();

            $table->foreign('id_pembelian')
                  ->references('id_pembelian')->on('pembelian')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('id_barang')
                  ->references('id_barang')->on('barang')
                  ->onDelete('set null')
                  ->onUpdate('set null');
        });

        // -------------------------------------------------------
        // 9. penjualan
        // -------------------------------------------------------
        Schema::create('penjualan', function (Blueprint $table) {
            $table->increments('id_penjualan');
            $table->string('pelanggan', 150)->nullable();
            $table->string('nomor_pelanggan', 50)->nullable();
            $table->string('kode_penjualan', 50);
            $table->date('tanggal_penjualan');
            $table->text('keterangan_penjualan')->nullable();
            $table->decimal('ongkir', 15, 2)->default(0.00);
            $table->text('alamat_pengiriman')->nullable();
            $table->enum('status_pengiriman', ['Pesanan Masuk', 'Diproses', 'Dikirim', 'Selesai'])
                  ->default('Pesanan Masuk');
            $table->timestamp('waktu_pesanan_masuk')->nullable();
            $table->timestamp('waktu_diproses')->nullable();
            $table->timestamp('waktu_dikirim')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->string('foto_pesanan', 255)->nullable();
            $table->text('catatan_kuitansi')->nullable();
            $table->enum('status_penjualan', ['Proses', 'Selesai']);
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });

        // -------------------------------------------------------
        // 10. penjualan_detail (depends on: penjualan, barang)
        // -------------------------------------------------------
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->increments('id_penjualan_detail');
            $table->unsignedInteger('id_penjualan');
            $table->unsignedInteger('id_barang')->nullable();
            $table->integer('id_produk')->nullable();
            $table->integer('id_pembelian_detail')->nullable();
            $table->string('harga_penjualan', 50)->nullable();
            $table->integer('jml_penjualan');
            $table->integer('qty_produk_jadi_terpakai')->default(0);
            $table->integer('qty_racik')->default(0);
            $table->text('catatan_detail')->nullable();

            $table->foreign('id_penjualan')
                  ->references('id_penjualan')->on('penjualan')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('id_barang')
                  ->references('id_barang')->on('barang')
                  ->onDelete('set null')
                  ->onUpdate('set null');
        });

        // -------------------------------------------------------
        // 11. penjualan_pembayaran (depends on: penjualan)
        // -------------------------------------------------------
        Schema::create('penjualan_pembayaran', function (Blueprint $table) {
            $table->increments('id_penjualan_pembayaran');
            $table->unsignedInteger('id_penjualan');
            $table->enum('metode_pembayaran', ['Tunai', 'Transfer'])->nullable();
            $table->string('metode_detail', 150)->nullable();
            $table->string('nominal_pembayaran', 50)->nullable();
            $table->date('tanggal_pembayaran')->nullable();

            $table->foreign('id_penjualan')
                  ->references('id_penjualan')->on('penjualan')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        // -------------------------------------------------------
        // 12. pemakaian_bahan (depends on: penjualan_detail, barang)
        // -------------------------------------------------------
        Schema::create('pemakaian_bahan', function (Blueprint $table) {
            $table->increments('id_pemakaian_bahan');
            $table->unsignedInteger('id_penjualan_detail');
            $table->unsignedInteger('id_barang');
            $table->decimal('qty_pakai', 12, 2)->default(0.00);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->index('id_penjualan_detail', 'pemakaian_bahan_id_penjualan_detail_index');
            $table->index('id_barang', 'pemakaian_bahan_id_barang_index');
        });
    }

    public function down(): void
    {
        // Drop in reverse dependency order
        Schema::dropIfExists('pemakaian_bahan');
        Schema::dropIfExists('penjualan_pembayaran');
        Schema::dropIfExists('penjualan_detail');
        Schema::dropIfExists('penjualan');
        Schema::dropIfExists('pembelian_detail');
        Schema::dropIfExists('pembelian');
        Schema::dropIfExists('detail_produk');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('biodata');
    }
};