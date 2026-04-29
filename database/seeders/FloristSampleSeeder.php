<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FloristSampleSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $ownerId = $this->seedOwner();
            $kategoriIds = $this->seedKategori($ownerId);
            $supplierIds = $this->seedSupplier();
            $barangIds = $this->seedBahan($ownerId, $kategoriIds);
            $produkIds = $this->seedProduk($ownerId, $barangIds);
            $this->seedPembelian($ownerId, $supplierIds, $barangIds);
            $this->seedPenjualan($ownerId, $barangIds, $produkIds);
        });
    }

    private function seedOwner()
    {
        $user = DB::table('users')->where('level', 'Owner')->orderBy('id')->first();
        if (!$user) {
            $user = DB::table('users')->where('email', 'owner@floris.local')->first();
        }
        if ($user) {
            DB::table('users')->where('id', $user->id)->update([
                'level' => 'Owner',
                'status' => 'Active',
                'updated_at' => now(),
            ]);

            DB::table('biodata')->updateOrInsert(
                ['id_user' => $user->id],
                [
                    'telepon' => '081234567890',
                    'alamat' => 'Brebes, Jawa Tengah',
                    'foto' => null,
                ]
            );

            return $user->id;
        }

        $id = DB::table('users')->insertGetId([
            'name' => 'Owner Floris',
            'email' => 'owner@floris.local',
            'password' => Hash::make('password'),
            'level' => 'Owner',
            'status' => 'Active',
            'token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('biodata')->insert([
            'id_user' => $id,
            'telepon' => '081234567890',
            'alamat' => 'Brebes, Jawa Tengah',
            'foto' => null,
        ]);

        return $id;
    }

    private function seedKategori($ownerId)
    {
        $rows = [
            'Bunga Segar',
            'Daun dan Fillers',
            'Aksesoris Bouquet',
            'Kemasan',
        ];

        $ids = [];
        foreach ($rows as $nama) {
            $existing = DB::table('kategori')->where('nama_kategori', $nama)->first();
            if ($existing) {
                $ids[$nama] = $existing->id_kategori;
                continue;
            }

            $ids[$nama] = DB::table('kategori')->insertGetId([
                'nama_kategori' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => $ownerId,
                'updated_by' => null,
            ]);
        }

        return $ids;
    }

    private function seedSupplier()
    {
        $rows = [
            [
                'nama_supplier' => 'PT Mawar Indah',
                'telepon_supplier' => '081100000001',
                'alamat_supplier' => 'Bandung',
            ],
            [
                'nama_supplier' => 'CV Floral Nusantara',
                'telepon_supplier' => '081100000002',
                'alamat_supplier' => 'Semarang',
            ],
        ];

        $ids = [];
        foreach ($rows as $row) {
            $existing = DB::table('supplier')->where('nama_supplier', $row['nama_supplier'])->first();
            if ($existing) {
                $ids[$row['nama_supplier']] = $existing->id_supplier;
                continue;
            }

            $ids[$row['nama_supplier']] = DB::table('supplier')->insertGetId(array_merge($row, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        return $ids;
    }

    private function seedBahan($ownerId, $kategoriIds)
    {
        $rows = [
            ['kode_barang' => 'BHN001', 'nama_barang' => 'Mawar Merah', 'id_kategori' => $kategoriIds['Bunga Segar'], 'satuan_barang' => 'tangkai', 'stok_minimum' => 20, 'harga_default' => 8000],
            ['kode_barang' => 'BHN002', 'nama_barang' => 'Baby Breath', 'id_kategori' => $kategoriIds['Daun dan Fillers'], 'satuan_barang' => 'ikat', 'stok_minimum' => 10, 'harga_default' => 25000],
            ['kode_barang' => 'BHN003', 'nama_barang' => 'Daun Ruskus', 'id_kategori' => $kategoriIds['Daun dan Fillers'], 'satuan_barang' => 'ikat', 'stok_minimum' => 10, 'harga_default' => 15000],
            ['kode_barang' => 'BHN004', 'nama_barang' => 'Wrapping Paper Pink', 'id_kategori' => $kategoriIds['Kemasan'], 'satuan_barang' => 'lembar', 'stok_minimum' => 30, 'harga_default' => 3000],
            ['kode_barang' => 'BHN005', 'nama_barang' => 'Pita Satin', 'id_kategori' => $kategoriIds['Aksesoris Bouquet'], 'satuan_barang' => 'meter', 'stok_minimum' => 20, 'harga_default' => 2000],
        ];

        $ids = [];
        foreach ($rows as $row) {
            $existing = DB::table('barang')->where('kode_barang', $row['kode_barang'])->first();
            $payload = array_merge($row, [
                'gambar' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => $ownerId,
                'updated_by' => null,
            ]);

            if ($existing) {
                DB::table('barang')->where('id_barang', $existing->id_barang)->update([
                    'id_kategori' => $payload['id_kategori'],
                    'nama_barang' => $payload['nama_barang'],
                    'satuan_barang' => $payload['satuan_barang'],
                    'stok_minimum' => $payload['stok_minimum'],
                    'harga_default' => $payload['harga_default'],
                    'updated_at' => now(),
                    'updated_by' => $ownerId,
                ]);
                $ids[$row['kode_barang']] = $existing->id_barang;
                continue;
            }

            $ids[$row['kode_barang']] = DB::table('barang')->insertGetId($payload);
        }

        return $ids;
    }

    private function seedProduk($ownerId, $barangIds)
    {
        $produkRows = [
            [
                'kode_produk' => 'PRD001',
                'nama_produk' => 'Bouquet Mawar Pink',
                'harga_jual' => 125000,
                'stok_produk_jadi' => 3,
                'deskripsi' => 'Bouquet mawar untuk hadiah ulang tahun.',
                'detail' => [
                    ['kode_barang' => 'BHN001', 'jumlah_pakai' => 7],
                    ['kode_barang' => 'BHN002', 'jumlah_pakai' => 0.5],
                    ['kode_barang' => 'BHN004', 'jumlah_pakai' => 2],
                    ['kode_barang' => 'BHN005', 'jumlah_pakai' => 1.5],
                ],
            ],
            [
                'kode_produk' => 'PRD002',
                'nama_produk' => 'Bouquet Mawar Premium',
                'harga_jual' => 185000,
                'stok_produk_jadi' => 1,
                'deskripsi' => 'Bouquet premium dengan filler lengkap.',
                'detail' => [
                    ['kode_barang' => 'BHN001', 'jumlah_pakai' => 12],
                    ['kode_barang' => 'BHN002', 'jumlah_pakai' => 1],
                    ['kode_barang' => 'BHN003', 'jumlah_pakai' => 1],
                    ['kode_barang' => 'BHN004', 'jumlah_pakai' => 3],
                    ['kode_barang' => 'BHN005', 'jumlah_pakai' => 2],
                ],
            ],
        ];

        $ids = [];
        foreach ($produkRows as $row) {
            $existing = DB::table('produk')->where('kode_produk', $row['kode_produk'])->first();
            $payload = [
                'kode_produk' => $row['kode_produk'],
                'nama_produk' => $row['nama_produk'],
                'harga_jual' => $row['harga_jual'],
                'stok_produk_jadi' => $row['stok_produk_jadi'],
                'gambar' => null,
                'deskripsi' => $row['deskripsi'],
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => $ownerId,
                'updated_by' => null,
            ];

            if ($existing) {
                DB::table('produk')->where('id_produk', $existing->id_produk)->update([
                    'nama_produk' => $payload['nama_produk'],
                    'harga_jual' => $payload['harga_jual'],
                    'stok_produk_jadi' => $payload['stok_produk_jadi'],
                    'deskripsi' => $payload['deskripsi'],
                    'updated_at' => now(),
                    'updated_by' => $ownerId,
                ]);
                $produkId = $existing->id_produk;
            } else {
                $produkId = DB::table('produk')->insertGetId($payload);
            }

            DB::table('detail_produk')->where('id_produk', $produkId)->delete();
            foreach ($row['detail'] as $detail) {
                DB::table('detail_produk')->insert([
                    'id_produk' => $produkId,
                    'id_barang' => $barangIds[$detail['kode_barang']],
                    'jumlah_pakai' => $detail['jumlah_pakai'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $ids[$row['kode_produk']] = $produkId;
        }

        return $ids;
    }

    private function seedPembelian($ownerId, $supplierIds, $barangIds)
    {
        $kode = 'PMB9001';
        $pembelian = DB::table('pembelian')->where('kode_pembelian', $kode)->first();
        if (!$pembelian) {
            $pembelianId = DB::table('pembelian')->insertGetId([
                'id_supplier' => $supplierIds['PT Mawar Indah'],
                'kode_pembelian' => $kode,
                'tanggal_pembelian' => now()->subDays(5)->format('Y-m-d'),
                'status_pembelian' => 'Gudang',
                'keterangan_pembelian' => 'Stok awal bahan florist.',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => $ownerId,
                'updated_by' => null,
            ]);

            $detailRows = [
                ['kode_barang' => 'BHN001', 'harga_beli' => 6000, 'harga_jual' => 8000, 'jml_pembelian' => 60],
                ['kode_barang' => 'BHN002', 'harga_beli' => 18000, 'harga_jual' => 25000, 'jml_pembelian' => 20],
                ['kode_barang' => 'BHN003', 'harga_beli' => 10000, 'harga_jual' => 15000, 'jml_pembelian' => 15],
                ['kode_barang' => 'BHN004', 'harga_beli' => 1800, 'harga_jual' => 3000, 'jml_pembelian' => 80],
                ['kode_barang' => 'BHN005', 'harga_beli' => 1200, 'harga_jual' => 2000, 'jml_pembelian' => 50],
            ];

            foreach ($detailRows as $detail) {
                DB::table('pembelian_detail')->insert([
                    'id_pembelian' => $pembelianId,
                    'id_barang' => $barangIds[$detail['kode_barang']],
                    'harga_beli' => $detail['harga_beli'],
                    'harga_jual' => $detail['harga_jual'],
                    'jml_pembelian' => $detail['jml_pembelian'],
                    'tanggal_exp' => null,
                ]);
            }
        }
    }

    private function seedPenjualan($ownerId, $barangIds, $produkIds)
    {
        if (DB::table('penjualan')->where('kode_penjualan', 'PNJ9001')->exists()) {
            return;
        }

        $penjualanId = DB::table('penjualan')->insertGetId([
            'pelanggan' => 'Nadia Putri',
            'nomor_pelanggan' => '081298765432',
            'kode_penjualan' => 'PNJ9001',
            'tanggal_penjualan' => now()->subDays(2)->format('Y-m-d'),
            'keterangan_penjualan' => 'Pesanan bouquet wisuda.',
            'ongkir' => 15000,
            'alamat_pengiriman' => 'Jl. Veteran No. 12, Brebes',
            'status_pengiriman' => 'Diproses',
            'waktu_pesanan_masuk' => now()->subDays(2),
            'waktu_diproses' => now()->subDays(2)->addHour(),
            'waktu_dikirim' => null,
            'waktu_selesai' => null,
            'foto_pesanan' => null,
            'catatan_kuitansi' => 'Titip ucapan selamat wisuda.',
            'status_penjualan' => 'Proses',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $ownerId,
            'updated_by' => null,
        ]);

        $detailProdukId = DB::table('penjualan_detail')->insertGetId([
            'id_penjualan' => $penjualanId,
            'id_barang' => null,
            'id_produk' => $produkIds['PRD001'],
            'id_pembelian_detail' => null,
            'harga_penjualan' => 125000,
            'jml_penjualan' => 2,
            'qty_produk_jadi_terpakai' => 2,
            'qty_racik' => 0,
            'catatan_detail' => 'Bouquet utama',
        ]);

        $detailBahanId = DB::table('penjualan_detail')->insertGetId([
            'id_penjualan' => $penjualanId,
            'id_barang' => $barangIds['BHN005'],
            'id_produk' => null,
            'id_pembelian_detail' => null,
            'harga_penjualan' => 5000,
            'jml_penjualan' => 2,
            'qty_produk_jadi_terpakai' => 0,
            'qty_racik' => 0,
            'catatan_detail' => 'Tambahan pita',
        ]);

        DB::table('produk')->where('id_produk', $produkIds['PRD001'])->decrement('stok_produk_jadi', 2);

        DB::table('penjualan_pembayaran')->insert([
            [
                'id_penjualan' => $penjualanId,
                'metode_pembayaran' => 'Transfer',
                'metode_detail' => 'BCA',
                'nominal_pembayaran' => 100000,
                'tanggal_pembayaran' => now()->subDays(2)->format('Y-m-d'),
            ],
            [
                'id_penjualan' => $penjualanId,
                'metode_pembayaran' => 'Tunai',
                'metode_detail' => null,
                'nominal_pembayaran' => 175000,
                'tanggal_pembayaran' => now()->subDay()->format('Y-m-d'),
            ],
        ]);

        $penjualanRacikId = DB::table('penjualan')->insertGetId([
            'pelanggan' => 'Rina Maharani',
            'nomor_pelanggan' => '081377700123',
            'kode_penjualan' => 'PNJ9002',
            'tanggal_penjualan' => now()->subDay()->format('Y-m-d'),
            'keterangan_penjualan' => 'Pesanan bouquet premium custom.',
            'ongkir' => 20000,
            'alamat_pengiriman' => 'Jl. Ahmad Yani No. 7, Tegal',
            'status_pengiriman' => 'Selesai',
            'waktu_pesanan_masuk' => now()->subDay(),
            'waktu_diproses' => now()->subDay()->addHour(),
            'waktu_dikirim' => now()->subDay()->addHours(3),
            'waktu_selesai' => now()->subDay()->addHours(6),
            'foto_pesanan' => null,
            'catatan_kuitansi' => 'Request warna wrap nude.',
            'status_penjualan' => 'Selesai',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $ownerId,
            'updated_by' => null,
        ]);

        $detailProdukRacikId = DB::table('penjualan_detail')->insertGetId([
            'id_penjualan' => $penjualanRacikId,
            'id_barang' => null,
            'id_produk' => $produkIds['PRD002'],
            'id_pembelian_detail' => null,
            'harga_penjualan' => 185000,
            'jml_penjualan' => 2,
            'qty_produk_jadi_terpakai' => 1,
            'qty_racik' => 1,
            'catatan_detail' => '1 stok jadi, 1 diracik',
        ]);

        $recipeRows = [
            ['kode_barang' => 'BHN001', 'qty_pakai' => 12],
            ['kode_barang' => 'BHN002', 'qty_pakai' => 1],
            ['kode_barang' => 'BHN003', 'qty_pakai' => 1],
            ['kode_barang' => 'BHN004', 'qty_pakai' => 3],
            ['kode_barang' => 'BHN005', 'qty_pakai' => 2],
        ];

        foreach ($recipeRows as $row) {
            DB::table('pemakaian_bahan')->insert([
                'id_penjualan_detail' => $detailProdukRacikId,
                'id_barang' => $barangIds[$row['kode_barang']],
                'qty_pakai' => $row['qty_pakai'],
                'keterangan' => 'Sample racik produk premium',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('produk')->where('id_produk', $produkIds['PRD002'])->decrement('stok_produk_jadi', 1);

        DB::table('penjualan_pembayaran')->insert([
            'id_penjualan' => $penjualanRacikId,
            'metode_pembayaran' => 'Transfer',
            'metode_detail' => 'DANA',
            'nominal_pembayaran' => 390000,
            'tanggal_pembayaran' => now()->subDay()->format('Y-m-d'),
        ]);
    }
}
