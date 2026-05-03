<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloristSampleSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key sementara agar insert data bebas urutan
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // -------------------------------------------------------
        // 1. users
        // -------------------------------------------------------
        DB::table('users')->updateOrInsert([
            [
                'id'         => 1,
                'name'       => 'Dani Trisna',
                'email'      => 'danitrisna@gmail.com',
                'password'   => '$2y$10$CbUBb6RHKpmB2Vwx1LG6EusfV.UsUXOLXjCAM0mJXbZZwbCPuMGS2',
                'level'      => 'Owner',
                'status'     => 'Active',
                'token'      => null,
                'created_at' => '2024-01-25 11:03:55',
                'updated_at' => '2026-04-29 09:25:03',
            ],
        ]);

        // -------------------------------------------------------
        // 2. biodata
        // -------------------------------------------------------
        DB::table('biodata')->updateOrInsert([
            [
                'id_biodata' => 1,
                'id_user'    => 1,
                'telepon'    => '081234567890',
                'alamat'     => 'Brebes, Jawa Tengah',
                'foto'       => null,
            ],
        ]);

        // -------------------------------------------------------
        // 3. kategori
        // -------------------------------------------------------
        DB::table('kategori')->updateOrInsert([
            [
                'id_kategori'    => 5,
                'nama_kategori'  => 'Bunga Segar',
                'created_at'     => '2026-04-29 09:25:03',
                'updated_at'     => '2026-04-29 09:25:03',
                'created_by'     => 1,
                'updated_by'     => null,
            ],
            [
                'id_kategori'    => 6,
                'nama_kategori'  => 'Daun dan Fillers',
                'created_at'     => '2026-04-29 09:25:03',
                'updated_at'     => '2026-04-29 09:25:03',
                'created_by'     => 1,
                'updated_by'     => null,
            ],
            [
                'id_kategori'    => 7,
                'nama_kategori'  => 'Aksesoris Bouquet',
                'created_at'     => '2026-04-29 09:25:03',
                'updated_at'     => '2026-04-29 09:25:03',
                'created_by'     => 1,
                'updated_by'     => null,
            ],
            [
                'id_kategori'    => 8,
                'nama_kategori'  => 'Kemasan',
                'created_at'     => '2026-04-29 09:25:03',
                'updated_at'     => '2026-04-29 09:25:03',
                'created_by'     => 1,
                'updated_by'     => null,
            ],
        ]);

        // -------------------------------------------------------
        // 4. supplier
        // -------------------------------------------------------
        DB::table('supplier')->updateOrInsert([
            [
                'id_supplier'       => 3,
                'nama_supplier'     => 'PT Mawar Indah',
                'telepon_supplier'  => '081100000001',
                'alamat_supplier'   => 'Bandung',
                'created_at'        => '2026-04-29 09:25:03',
                'updated_at'        => '2026-04-29 09:25:03',
            ],
            [
                'id_supplier'       => 4,
                'nama_supplier'     => 'CV Floral Nusantara',
                'telepon_supplier'  => '081100000002',
                'alamat_supplier'   => 'Semarang',
                'created_at'        => '2026-04-29 09:25:03',
                'updated_at'        => '2026-04-29 09:25:03',
            ],
        ]);

        // -------------------------------------------------------
        // 5. barang
        // -------------------------------------------------------
        DB::table('barang')->updateOrInsert([
            [
                'id_barang'     => 6,
                'id_kategori'   => 5,
                'kode_barang'   => 'BHN001',
                'nama_barang'   => 'Mawar Merah',
                'satuan_barang' => 'tangkai',
                'gambar'        => '20260429203526_download.jpg',
                'stok_minimum'  => 20,
                'harga_default' => 80000000.00,
                'created_at'    => '2026-04-29 09:25:03',
                'updated_at'    => '2026-04-29 13:35:26',
                'created_by'    => 1,
                'updated_by'    => 1,
            ],
            [
                'id_barang'     => 7,
                'id_kategori'   => 6,
                'kode_barang'   => 'BHN002',
                'nama_barang'   => 'Baby Breath',
                'satuan_barang' => 'ikat',
                'gambar'        => '20260429203835_Baby\'s_Breath_-_Gypsophila.jpg',
                'stok_minimum'  => 10,
                'harga_default' => 2500000.00,
                'created_at'    => '2026-04-29 09:25:03',
                'updated_at'    => '2026-04-29 13:38:35',
                'created_by'    => 1,
                'updated_by'    => 1,
            ],
            [
                'id_barang'     => 8,
                'id_kategori'   => 6,
                'kode_barang'   => 'BHN003',
                'nama_barang'   => 'Daun Ruskus',
                'satuan_barang' => 'ikat',
                'gambar'        => '20260429203925_Daun_ruskus_daun_segar_hijau_alami_bijian_bahan_buket.jpg',
                'stok_minimum'  => 10,
                'harga_default' => 1500000.00,
                'created_at'    => '2026-04-29 09:25:03',
                'updated_at'    => '2026-04-29 13:39:25',
                'created_by'    => 1,
                'updated_by'    => 1,
            ],
            [
                'id_barang'     => 9,
                'id_kategori'   => 8,
                'kode_barang'   => 'BHN004',
                'nama_barang'   => 'Wrapping Paper Pink',
                'satuan_barang' => 'lembar',
                'gambar'        => '20260429204041_Nonwoven_Premium_Embossed_Wrap_Sheets_Pink_Pk_50_(50x70cm).jpg',
                'stok_minimum'  => 30,
                'harga_default' => 300000.00,
                'created_at'    => '2026-04-29 09:25:03',
                'updated_at'    => '2026-04-29 13:40:41',
                'created_by'    => 1,
                'updated_by'    => 1,
            ],
            [
                'id_barang'     => 10,
                'id_kategori'   => 7,
                'kode_barang'   => 'BHN005',
                'nama_barang'   => 'Pita Satin',
                'satuan_barang' => 'meter',
                'gambar'        => '20260429204234_Designs.jpg',
                'stok_minimum'  => 20,
                'harga_default' => 200000.00,
                'created_at'    => '2026-04-29 09:25:03',
                'updated_at'    => '2026-04-29 13:42:34',
                'created_by'    => 1,
                'updated_by'    => 1,
            ],
        ]);

        // -------------------------------------------------------
        // 6. produk
        // -------------------------------------------------------
        DB::table('produk')->updateOrInsert([
            [
                'id_produk'         => 3,
                'kode_produk'       => 'PRD001',
                'nama_produk'       => 'Bouquet Mawar Pink',
                'harga_jual'        => 12500000.00,
                'stok_produk_jadi'  => 1,
                'gambar'            => '20260429204501_Flower_bouquet___design___Banner_Editing.jpg',
                'deskripsi'         => 'Bouquet mawar untuk hadiah ulang tahun.',
                'created_at'        => '2026-04-29 09:25:03',
                'updated_at'        => '2026-04-29 13:45:01',
                'created_by'        => 1,
                'updated_by'        => 1,
            ],
            [
                'id_produk'         => 4,
                'kode_produk'       => 'PRD002',
                'nama_produk'       => 'Bouquet Mawar Premium',
                'harga_jual'        => 18500000.00,
                'stok_produk_jadi'  => 0,
                'gambar'            => '20260429204555_Bouqet.jpg',
                'deskripsi'         => 'Bouquet premium dengan filler lengkap.',
                'created_at'        => '2026-04-29 09:25:03',
                'updated_at'        => '2026-04-29 13:45:55',
                'created_by'        => 1,
                'updated_by'        => 1,
            ],
        ]);

        // -------------------------------------------------------
        // 7. detail_produk
        // -------------------------------------------------------
        DB::table('detail_produk')->updateOrInsert([
            ['id_detail' => 23, 'id_produk' => 3, 'id_barang' => 7,  'jumlah_pakai' => 0.50, 'created_at' => '2026-04-29 13:45:01', 'updated_at' => '2026-04-29 13:45:01'],
            ['id_detail' => 24, 'id_produk' => 3, 'id_barang' => 6,  'jumlah_pakai' => 7.00, 'created_at' => '2026-04-29 13:45:01', 'updated_at' => '2026-04-29 13:45:01'],
            ['id_detail' => 25, 'id_produk' => 3, 'id_barang' => 10, 'jumlah_pakai' => 1.50, 'created_at' => '2026-04-29 13:45:01', 'updated_at' => '2026-04-29 13:45:01'],
            ['id_detail' => 26, 'id_produk' => 3, 'id_barang' => 9,  'jumlah_pakai' => 2.00, 'created_at' => '2026-04-29 13:45:01', 'updated_at' => '2026-04-29 13:45:01'],
            ['id_detail' => 27, 'id_produk' => 4, 'id_barang' => 7,  'jumlah_pakai' => 1.00, 'created_at' => '2026-04-29 13:45:55', 'updated_at' => '2026-04-29 13:45:55'],
            ['id_detail' => 28, 'id_produk' => 4, 'id_barang' => 8,  'jumlah_pakai' => 1.00, 'created_at' => '2026-04-29 13:45:55', 'updated_at' => '2026-04-29 13:45:55'],
            ['id_detail' => 29, 'id_produk' => 4, 'id_barang' => 6,  'jumlah_pakai' => 12.00,'created_at' => '2026-04-29 13:45:55', 'updated_at' => '2026-04-29 13:45:55'],
            ['id_detail' => 30, 'id_produk' => 4, 'id_barang' => 10, 'jumlah_pakai' => 2.00, 'created_at' => '2026-04-29 13:45:55', 'updated_at' => '2026-04-29 13:45:55'],
            ['id_detail' => 31, 'id_produk' => 4, 'id_barang' => 9,  'jumlah_pakai' => 3.00, 'created_at' => '2026-04-29 13:45:55', 'updated_at' => '2026-04-29 13:45:55'],
        ]);

        // -------------------------------------------------------
        // 8. pembelian
        // -------------------------------------------------------
        DB::table('pembelian')->updateOrInsert([
            [
                'id_pembelian'          => 1,
                'id_supplier'           => 3,
                'kode_pembelian'        => 'PMB9001',
                'tanggal_pembelian'     => '2026-04-24',
                'status_pembelian'      => 'Gudang',
                'keterangan_pembelian'  => 'Stok awal bahan florist.',
                'created_at'            => '2026-04-29 09:25:03',
                'updated_at'            => '2026-04-29 09:25:03',
                'created_by'            => 1,
                'updated_by'            => null,
            ],
        ]);

        // -------------------------------------------------------
        // 9. pembelian_detail
        // -------------------------------------------------------
        DB::table('pembelian_detail')->updateOrInsert([
            ['id_pembelian_detail' => 1, 'id_pembelian' => 1, 'id_barang' => 6,  'harga_beli' => '6000',  'harga_jual' => '8000',  'jml_pembelian' => 60, 'tanggal_exp' => null],
            ['id_pembelian_detail' => 2, 'id_pembelian' => 1, 'id_barang' => 7,  'harga_beli' => '18000', 'harga_jual' => '25000', 'jml_pembelian' => 20, 'tanggal_exp' => null],
            ['id_pembelian_detail' => 3, 'id_pembelian' => 1, 'id_barang' => 8,  'harga_beli' => '10000', 'harga_jual' => '15000', 'jml_pembelian' => 15, 'tanggal_exp' => null],
            ['id_pembelian_detail' => 4, 'id_pembelian' => 1, 'id_barang' => 9,  'harga_beli' => '1800',  'harga_jual' => '3000',  'jml_pembelian' => 80, 'tanggal_exp' => null],
            ['id_pembelian_detail' => 5, 'id_pembelian' => 1, 'id_barang' => 10, 'harga_beli' => '1200',  'harga_jual' => '2000',  'jml_pembelian' => 50, 'tanggal_exp' => null],
        ]);

        // -------------------------------------------------------
        // 10. penjualan
        // -------------------------------------------------------
        DB::table('penjualan')->updateOrInsert([
            [
                'id_penjualan'          => 1,
                'pelanggan'             => 'Nadia Putri',
                'nomor_pelanggan'       => '081298765432',
                'kode_penjualan'        => 'PNJ9001',
                'tanggal_penjualan'     => '2026-04-27',
                'keterangan_penjualan'  => 'Pesanan bouquet wisuda.',
                'ongkir'                => 15000.00,
                'alamat_pengiriman'     => 'Jl. Veteran No. 12, Brebes',
                'status_pengiriman'     => 'Diproses',
                'waktu_pesanan_masuk'   => '2026-04-27 09:25:03',
                'waktu_diproses'        => '2026-04-27 10:25:03',
                'waktu_dikirim'         => null,
                'waktu_selesai'         => null,
                'foto_pesanan'          => null,
                'catatan_kuitansi'      => 'Titip ucapan selamat wisuda.',
                'status_penjualan'      => 'Selesai',
                'created_at'            => '2026-04-29 09:25:03',
                'updated_at'            => '2026-04-29 12:54:50',
                'created_by'            => 1,
                'updated_by'            => null,
            ],
            [
                'id_penjualan'          => 2,
                'pelanggan'             => 'Rina Maharani',
                'nomor_pelanggan'       => '081377700123',
                'kode_penjualan'        => 'PNJ9002',
                'tanggal_penjualan'     => '2026-04-28',
                'keterangan_penjualan'  => 'Pesanan bouquet premium custom.',
                'ongkir'                => 20000.00,
                'alamat_pengiriman'     => 'Jl. Ahmad Yani No. 7, Tegal',
                'status_pengiriman'     => 'Selesai',
                'waktu_pesanan_masuk'   => '2026-04-28 09:25:03',
                'waktu_diproses'        => '2026-04-28 10:25:03',
                'waktu_dikirim'         => '2026-04-28 12:25:03',
                'waktu_selesai'         => '2026-04-28 15:25:03',
                'foto_pesanan'          => null,
                'catatan_kuitansi'      => 'Request warna wrap nude.',
                'status_penjualan'      => 'Selesai',
                'created_at'            => '2026-04-29 09:25:03',
                'updated_at'            => '2026-04-29 09:25:03',
                'created_by'            => 1,
                'updated_by'            => null,
            ],
            [
                'id_penjualan'          => 4,
                'pelanggan'             => 'Dimas Anggara',
                'nomor_pelanggan'       => '081726538849',
                'kode_penjualan'        => 'PNJ9003',
                'tanggal_penjualan'     => '2026-04-30',
                'keterangan_penjualan'  => 'Pesanan bouquet premium custom',
                'ongkir'                => 0.00,
                'alamat_pengiriman'     => 'Jl. Lasda No. 12, Brebes',
                'status_pengiriman'     => 'Pesanan Masuk',
                'waktu_pesanan_masuk'   => '2026-04-29 13:20:01',
                'waktu_diproses'        => null,
                'waktu_dikirim'         => null,
                'waktu_selesai'         => null,
                'foto_pesanan'          => '20260429202001_e55d5c7ca0b28a6cf32c13dc922ecac3@resize_w900_nl.webp',
                'catatan_kuitansi'      => 'Pesanan bouquet premium custom',
                'status_penjualan'      => 'Proses',
                'created_at'            => '2026-04-29 13:20:01',
                'updated_at'            => '2026-04-29 13:20:01',
                'created_by'            => null,
                'updated_by'            => null,
            ],
        ]);

        // -------------------------------------------------------
        // 11. penjualan_detail
        // -------------------------------------------------------
        DB::table('penjualan_detail')->updateOrInsert([
            [
                'id_penjualan_detail'       => 1,
                'id_penjualan'              => 1,
                'id_barang'                 => null,
                'id_produk'                 => 3,
                'id_pembelian_detail'       => null,
                'harga_penjualan'           => '125000',
                'jml_penjualan'             => 2,
                'qty_produk_jadi_terpakai'  => 2,
                'qty_racik'                 => 0,
                'catatan_detail'            => 'Bouquet utama',
            ],
            [
                'id_penjualan_detail'       => 2,
                'id_penjualan'              => 1,
                'id_barang'                 => 10,
                'id_produk'                 => null,
                'id_pembelian_detail'       => null,
                'harga_penjualan'           => '5000',
                'jml_penjualan'             => 2,
                'qty_produk_jadi_terpakai'  => 0,
                'qty_racik'                 => 0,
                'catatan_detail'            => 'Tambahan pita',
            ],
            [
                'id_penjualan_detail'       => 3,
                'id_penjualan'              => 2,
                'id_barang'                 => null,
                'id_produk'                 => 4,
                'id_pembelian_detail'       => null,
                'harga_penjualan'           => '185000',
                'jml_penjualan'             => 2,
                'qty_produk_jadi_terpakai'  => 1,
                'qty_racik'                 => 1,
                'catatan_detail'            => '1 stok jadi, 1 diracik',
            ],
            [
                'id_penjualan_detail'       => 9,
                'id_penjualan'              => 4,
                'id_barang'                 => null,
                'id_produk'                 => 4,
                'id_pembelian_detail'       => null,
                'harga_penjualan'           => '185000',
                'jml_penjualan'             => 1,
                'qty_produk_jadi_terpakai'  => 0,
                'qty_racik'                 => 1,
                'catatan_detail'            => null,
            ],
        ]);

        // -------------------------------------------------------
        // 12. penjualan_pembayaran
        // -------------------------------------------------------
        DB::table('penjualan_pembayaran')->updateOrInsert([
            ['id_penjualan_pembayaran' => 1, 'id_penjualan' => 1, 'metode_pembayaran' => 'Transfer', 'metode_detail' => 'BCA',  'nominal_pembayaran' => '100000', 'tanggal_pembayaran' => '2026-04-27'],
            ['id_penjualan_pembayaran' => 2, 'id_penjualan' => 1, 'metode_pembayaran' => 'Tunai',    'metode_detail' => null,   'nominal_pembayaran' => '175000', 'tanggal_pembayaran' => '2026-04-28'],
            ['id_penjualan_pembayaran' => 3, 'id_penjualan' => 2, 'metode_pembayaran' => 'Transfer', 'metode_detail' => 'DANA', 'nominal_pembayaran' => '390000', 'tanggal_pembayaran' => '2026-04-28'],
            ['id_penjualan_pembayaran' => 5, 'id_penjualan' => 4, 'metode_pembayaran' => 'Tunai',    'metode_detail' => null,   'nominal_pembayaran' => '185000', 'tanggal_pembayaran' => '2026-04-30'],
        ]);

        // -------------------------------------------------------
        // 13. pemakaian_bahan
        // -------------------------------------------------------
        DB::table('pemakaian_bahan')->updateOrInsert([
            ['id_pemakaian_bahan' => 1,  'id_penjualan_detail' => 3, 'id_barang' => 6,  'qty_pakai' => 12.00, 'keterangan' => 'Sample racik produk premium',                          'created_at' => '2026-04-29 09:25:03', 'updated_at' => '2026-04-29 09:25:03'],
            ['id_pemakaian_bahan' => 2,  'id_penjualan_detail' => 3, 'id_barang' => 7,  'qty_pakai' => 1.00,  'keterangan' => 'Sample racik produk premium',                          'created_at' => '2026-04-29 09:25:03', 'updated_at' => '2026-04-29 09:25:03'],
            ['id_pemakaian_bahan' => 3,  'id_penjualan_detail' => 3, 'id_barang' => 8,  'qty_pakai' => 1.00,  'keterangan' => 'Sample racik produk premium',                          'created_at' => '2026-04-29 09:25:03', 'updated_at' => '2026-04-29 09:25:03'],
            ['id_pemakaian_bahan' => 4,  'id_penjualan_detail' => 3, 'id_barang' => 9,  'qty_pakai' => 3.00,  'keterangan' => 'Sample racik produk premium',                          'created_at' => '2026-04-29 09:25:03', 'updated_at' => '2026-04-29 09:25:03'],
            ['id_pemakaian_bahan' => 5,  'id_penjualan_detail' => 3, 'id_barang' => 10, 'qty_pakai' => 2.00,  'keterangan' => 'Sample racik produk premium',                          'created_at' => '2026-04-29 09:25:03', 'updated_at' => '2026-04-29 09:25:03'],
            ['id_pemakaian_bahan' => 6,  'id_penjualan_detail' => 9, 'id_barang' => 6,  'qty_pakai' => 12.00, 'keterangan' => 'Pemakaian bahan untuk produk Bouquet Mawar Premium',   'created_at' => '2026-04-29 13:20:01', 'updated_at' => '2026-04-29 13:20:01'],
            ['id_pemakaian_bahan' => 7,  'id_penjualan_detail' => 9, 'id_barang' => 7,  'qty_pakai' => 1.00,  'keterangan' => 'Pemakaian bahan untuk produk Bouquet Mawar Premium',   'created_at' => '2026-04-29 13:20:01', 'updated_at' => '2026-04-29 13:20:01'],
            ['id_pemakaian_bahan' => 8,  'id_penjualan_detail' => 9, 'id_barang' => 8,  'qty_pakai' => 1.00,  'keterangan' => 'Pemakaian bahan untuk produk Bouquet Mawar Premium',   'created_at' => '2026-04-29 13:20:01', 'updated_at' => '2026-04-29 13:20:01'],
            ['id_pemakaian_bahan' => 9,  'id_penjualan_detail' => 9, 'id_barang' => 9,  'qty_pakai' => 3.00,  'keterangan' => 'Pemakaian bahan untuk produk Bouquet Mawar Premium',   'created_at' => '2026-04-29 13:20:01', 'updated_at' => '2026-04-29 13:20:01'],
            ['id_pemakaian_bahan' => 10, 'id_penjualan_detail' => 9, 'id_barang' => 10, 'qty_pakai' => 2.00,  'keterangan' => 'Pemakaian bahan untuk produk Bouquet Mawar Premium',   'created_at' => '2026-04-29 13:20:01', 'updated_at' => '2026-04-29 13:20:01'],
        ]);

        // Aktifkan kembali foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}