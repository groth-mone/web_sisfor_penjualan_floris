<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang', function (Blueprint $table) {
            if (!Schema::hasColumn('barang', 'gambar')) {
                $table->string('gambar')->nullable()->after('satuan_barang');
            }
            if (!Schema::hasColumn('barang', 'stok_minimum')) {
                $table->integer('stok_minimum')->default(5)->after('gambar');
            }
            if (!Schema::hasColumn('barang', 'harga_default')) {
                $table->decimal('harga_default', 15, 2)->default(0)->after('stok_minimum');
            }
        });

        if (!Schema::hasTable('produk')) {
            Schema::create('produk', function (Blueprint $table) {
                $table->increments('id_produk');
                $table->string('kode_produk', 100)->unique();
                $table->string('nama_produk', 200);
                $table->decimal('harga_jual', 15, 2)->default(0);
                $table->integer('stok_produk_jadi')->default(0);
                $table->string('gambar')->nullable();
                $table->text('deskripsi')->nullable();
                $table->timestamps();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
            });
        }

        if (!Schema::hasTable('detail_produk')) {
            Schema::create('detail_produk', function (Blueprint $table) {
                $table->increments('id_detail');
                $table->integer('id_produk')->index();
                $table->integer('id_barang')->index();
                $table->decimal('jumlah_pakai', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pemakaian_bahan')) {
            Schema::create('pemakaian_bahan', function (Blueprint $table) {
                $table->increments('id_pemakaian_bahan');
                $table->integer('id_penjualan_detail')->index();
                $table->integer('id_barang')->index();
                $table->decimal('qty_pakai', 12, 2)->default(0);
                $table->string('keterangan')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('penjualan', function (Blueprint $table) {
            if (!Schema::hasColumn('penjualan', 'ongkir')) {
                $table->decimal('ongkir', 15, 2)->default(0)->after('keterangan_penjualan');
            }
            if (!Schema::hasColumn('penjualan', 'alamat_pengiriman')) {
                $table->text('alamat_pengiriman')->nullable()->after('ongkir');
            }
            if (!Schema::hasColumn('penjualan', 'status_pengiriman')) {
                $table->enum('status_pengiriman', ['Pesanan Masuk', 'Diproses', 'Dikirim', 'Selesai'])
                    ->default('Pesanan Masuk')
                    ->after('alamat_pengiriman');
            }
            if (!Schema::hasColumn('penjualan', 'waktu_pesanan_masuk')) {
                $table->timestamp('waktu_pesanan_masuk')->nullable()->after('status_pengiriman');
            }
            if (!Schema::hasColumn('penjualan', 'waktu_diproses')) {
                $table->timestamp('waktu_diproses')->nullable()->after('waktu_pesanan_masuk');
            }
            if (!Schema::hasColumn('penjualan', 'waktu_dikirim')) {
                $table->timestamp('waktu_dikirim')->nullable()->after('waktu_diproses');
            }
            if (!Schema::hasColumn('penjualan', 'waktu_selesai')) {
                $table->timestamp('waktu_selesai')->nullable()->after('waktu_dikirim');
            }
            if (!Schema::hasColumn('penjualan', 'foto_pesanan')) {
                $table->string('foto_pesanan')->nullable()->after('waktu_selesai');
            }
            if (!Schema::hasColumn('penjualan', 'catatan_kuitansi')) {
                $table->text('catatan_kuitansi')->nullable()->after('foto_pesanan');
            }
        });

        Schema::table('penjualan_detail', function (Blueprint $table) {
            if (!Schema::hasColumn('penjualan_detail', 'id_produk')) {
                $table->integer('id_produk')->nullable()->after('id_barang');
            }
            if (!Schema::hasColumn('penjualan_detail', 'qty_produk_jadi_terpakai')) {
                $table->integer('qty_produk_jadi_terpakai')->default(0)->after('jml_penjualan');
            }
            if (!Schema::hasColumn('penjualan_detail', 'qty_racik')) {
                $table->integer('qty_racik')->default(0)->after('qty_produk_jadi_terpakai');
            }
            if (!Schema::hasColumn('penjualan_detail', 'catatan_detail')) {
                $table->text('catatan_detail')->nullable()->after('qty_racik');
            }
        });

        if (Schema::hasColumn('barang', 'stok_minimum') && Schema::hasColumn('barang', 'harga_default')) {
            DB::table('barang')->update([
                'stok_minimum' => DB::raw('COALESCE(stok_minimum, 5)'),
                'harga_default' => DB::raw('COALESCE(harga_default, 0)')
            ]);
        }

        if (Schema::hasColumn('penjualan', 'status_pengiriman') && Schema::hasColumn('penjualan', 'waktu_pesanan_masuk')) {
            DB::table('penjualan')
                ->whereNull('status_pengiriman')
                ->update([
                    'status_pengiriman' => 'Pesanan Masuk',
                    'waktu_pesanan_masuk' => DB::raw('COALESCE(created_at, NOW())')
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan_detail', function (Blueprint $table) {
            $dropColumns = [];
            foreach (['id_produk', 'qty_produk_jadi_terpakai', 'qty_racik', 'catatan_detail'] as $column) {
                if (Schema::hasColumn('penjualan_detail', $column)) {
                    $dropColumns[] = $column;
                }
            }
            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });

        Schema::table('penjualan', function (Blueprint $table) {
            $dropColumns = [];
            foreach ([
                'ongkir',
                'alamat_pengiriman',
                'status_pengiriman',
                'waktu_pesanan_masuk',
                'waktu_diproses',
                'waktu_dikirim',
                'waktu_selesai',
                'foto_pesanan',
                'catatan_kuitansi'
            ] as $column) {
                if (Schema::hasColumn('penjualan', $column)) {
                    $dropColumns[] = $column;
                }
            }
            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });

        if (Schema::hasTable('pemakaian_bahan')) {
            Schema::drop('pemakaian_bahan');
        }
        if (Schema::hasTable('detail_produk')) {
            Schema::drop('detail_produk');
        }
        if (Schema::hasTable('produk')) {
            Schema::drop('produk');
        }

        Schema::table('barang', function (Blueprint $table) {
            $dropColumns = [];
            foreach (['gambar', 'stok_minimum', 'harga_default'] as $column) {
                if (Schema::hasColumn('barang', $column)) {
                    $dropColumns[] = $column;
                }
            }
            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
