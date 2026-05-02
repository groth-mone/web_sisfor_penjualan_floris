<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // ==================== CEK DAN BUAT TABEL BARANG ====================
        if (!Schema::hasTable('barang')) {
            // Buat tabel barang dari awal
            Schema::create('barang', function (Blueprint $table) {
                $table->increments('id_barang');
                $table->unsignedInteger('id_kategori')->nullable();
                $table->string('kode_barang', 200);
                $table->string('nama_barang', 250);
                $table->string('satuan_barang', 150);
                $table->string('gambar', 255)->nullable();
                $table->integer('stok_minimum')->default(5);
                $table->decimal('harga_default', 15, 2)->default(0);
                $table->timestamps();
                $table->unsignedInteger('created_by')->nullable();
                $table->unsignedInteger('updated_by')->nullable();
                
                $table->index('id_kategori');
            });
            
            echo "Tabel 'barang' berhasil dibuat.\n";
        } else {
            // Tabel sudah ada, tambahkan kolom jika perlu
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
            
            echo "Tabel 'barang' sudah ada, kolom ditambahkan.\n";
        }

        // ==================== TABEL PRODUK ====================
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
                $table->unsignedInteger('created_by')->nullable();
                $table->unsignedInteger('updated_by')->nullable();
            });
            
            echo "Tabel 'produk' berhasil dibuat.\n";
        }

        // ==================== TABEL DETAIL PRODUK ====================
        if (!Schema::hasTable('detail_produk')) {
            Schema::create('detail_produk', function (Blueprint $table) {
                $table->increments('id_detail');
                $table->unsignedInteger('id_produk');
                $table->unsignedInteger('id_barang');
                $table->decimal('jumlah_pakai', 12, 2)->default(0);
                $table->timestamps();
                
                $table->index('id_produk');
                $table->index('id_barang');
                
                // Foreign key (opsional, jika tabel sudah ada)
                // $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
                // $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            });
            
            echo "Tabel 'detail_produk' berhasil dibuat.\n";
        }

        // ==================== TABEL PEMAKAIAN BAHAN ====================
        if (!Schema::hasTable('pemakaian_bahan')) {
            Schema::create('pemakaian_bahan', function (Blueprint $table) {
                $table->increments('id_pemakaian_bahan');
                $table->unsignedInteger('id_penjualan_detail');
                $table->unsignedInteger('id_barang');
                $table->decimal('qty_pakai', 12, 2)->default(0);
                $table->string('keterangan')->nullable();
                $table->timestamps();
                
                $table->index('id_penjualan_detail');
                $table->index('id_barang');
            });
            
            echo "Tabel 'pemakaian_bahan' berhasil dibuat.\n";
        }

        // ==================== MODIFIKASI TABEL PENJUALAN ====================
        if (Schema::hasTable('penjualan')) {
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
            
            echo "Tabel 'penjualan' berhasil dimodifikasi.\n";
        }

        // ==================== MODIFIKASI TABEL PENJUALAN DETAIL ====================
        if (Schema::hasTable('penjualan_detail')) {
            Schema::table('penjualan_detail', function (Blueprint $table) {
                if (!Schema::hasColumn('penjualan_detail', 'id_produk')) {
                    $table->unsignedInteger('id_produk')->nullable()->after('id_barang');
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
            
            echo "Tabel 'penjualan_detail' berhasil dimodifikasi.\n";
        }

        // ==================== UPDATE DATA DEFAULT ====================
        try {
            if (Schema::hasTable('barang') && 
                Schema::hasColumn('barang', 'stok_minimum') && 
                Schema::hasColumn('barang', 'harga_default')) {
                DB::table('barang')->update([
                    'stok_minimum' => DB::raw('COALESCE(stok_minimum, 5)'),
                    'harga_default' => DB::raw('COALESCE(harga_default, 0)')
                ]);
            }
        } catch (\Exception $e) {
            echo "Warning: Gagal update data barang - " . $e->getMessage() . "\n";
        }

        try {
            if (Schema::hasTable('penjualan') && 
                Schema::hasColumn('penjualan', 'status_pengiriman') && 
                Schema::hasColumn('penjualan', 'waktu_pesanan_masuk')) {
                DB::table('penjualan')
                    ->whereNull('status_pengiriman')
                    ->update([
                        'status_pengiriman' => 'Pesanan Masuk',
                        'waktu_pesanan_masuk' => DB::raw('COALESCE(created_at, NOW())')
                    ]);
            }
        } catch (\Exception $e) {
            echo "Warning: Gagal update data penjualan - " . $e->getMessage() . "\n";
        }
        
        echo "Migration completed successfully!\n";
    }

    public function down()
    {
        // Hapus kolom dari penjualan_detail
        if (Schema::hasTable('penjualan_detail')) {
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
        }

        // Hapus kolom dari penjualan
        if (Schema::hasTable('penjualan')) {
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
        }

        // Drop tabel (urutan dibalik karena foreign key)
        if (Schema::hasTable('pemakaian_bahan')) {
            Schema::dropIfExists('pemakaian_bahan');
        }
        if (Schema::hasTable('detail_produk')) {
            Schema::dropIfExists('detail_produk');
        }
        if (Schema::hasTable('produk')) {
            Schema::dropIfExists('produk');
        }

        // Hapus kolom dari barang (jangan drop tabelnya)
        if (Schema::hasTable('barang')) {
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
    }
};