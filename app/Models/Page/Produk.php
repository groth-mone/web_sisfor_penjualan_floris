<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    private const KODE_PREFIX = 'PRD';
    private const KODE_LENGTH = 3;

    public static function getProduk()
    {
        return self::query()
            ->leftJoin(DB::raw('(SELECT id_produk, SUM(jumlah_pakai) as total_bahan FROM detail_produk GROUP BY id_produk) as dp'), 'dp.id_produk', '=', 'produk.id_produk')
            ->selectRaw('
                produk.*,
                COALESCE(dp.total_bahan, 0) as total_bahan
            ')
            ->orderBy('produk.kode_produk', 'ASC')
            ->get();
    }

    public static function getSelectOptions()
    {
        return self::query()
            ->selectRaw('
                id_produk as id_barang,
                kode_produk as kode_barang,
                nama_produk as nama_barang,
                harga_jual as harga_jual,
                stok_produk_jadi as stok_sekarang,
                gambar
            ')
            ->orderBy('kode_produk', 'ASC')
            ->get();
    }

    public static function getEdit($idProduk)
    {
        $produk = self::findOrFail($idProduk);
        $detail = DB::table('detail_produk')
            ->join('barang', 'barang.id_barang', '=', 'detail_produk.id_barang')
            ->leftJoin(DB::raw('(SELECT id_barang, SUM(jml_pembelian) as stok_masuk FROM pembelian_detail GROUP BY id_barang) as bm'), 'bm.id_barang', '=', 'barang.id_barang')
            ->leftJoin(DB::raw('(SELECT id_barang, SUM(jml_penjualan) as stok_keluar_lama FROM penjualan_detail WHERE id_produk IS NULL GROUP BY id_barang) as bk'), 'bk.id_barang', '=', 'barang.id_barang')
            ->leftJoin(DB::raw('(SELECT id_barang, SUM(qty_pakai) as stok_keluar_resep FROM pemakaian_bahan GROUP BY id_barang) as pb'), 'pb.id_barang', '=', 'barang.id_barang')
            ->where('detail_produk.id_produk', $idProduk)
            ->selectRaw('
                detail_produk.*,
                barang.kode_barang,
                barang.nama_barang,
                barang.satuan_barang,
                (COALESCE(bm.stok_masuk, 0) - COALESCE(bk.stok_keluar_lama, 0) - COALESCE(pb.stok_keluar_resep, 0)) as stok_bahan
            ')
            ->orderBy('barang.nama_barang', 'ASC')
            ->get();

        return [
            'produk' => $produk,
            'detail' => $detail,
        ];
    }

    public static function generateKodeProduk()
    {
        $lastCode = self::query()
            ->where('kode_produk', 'like', self::KODE_PREFIX . '%')
            ->orderByRaw('CAST(SUBSTRING(kode_produk, ' . (strlen(self::KODE_PREFIX) + 1) . ') AS UNSIGNED) DESC')
            ->value('kode_produk');

        $nextNumber = 1;
        if ($lastCode && preg_match('/^' . self::KODE_PREFIX . '(\d+)$/', $lastCode, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        return self::KODE_PREFIX . str_pad((string) $nextNumber, self::KODE_LENGTH, '0', STR_PAD_LEFT);
    }

    public static function getRecipe($idProduk)
    {
        return DB::table('detail_produk')
            ->join('barang', 'barang.id_barang', '=', 'detail_produk.id_barang')
            ->leftJoin(DB::raw('(SELECT id_barang, SUM(jml_pembelian) as stok_masuk FROM pembelian_detail GROUP BY id_barang) as bm'), 'bm.id_barang', '=', 'barang.id_barang')
            ->leftJoin(DB::raw('(SELECT id_barang, SUM(jml_penjualan) as stok_keluar_lama FROM penjualan_detail WHERE id_produk IS NULL GROUP BY id_barang) as bk'), 'bk.id_barang', '=', 'barang.id_barang')
            ->leftJoin(DB::raw('(SELECT id_barang, SUM(qty_pakai) as stok_keluar_resep FROM pemakaian_bahan GROUP BY id_barang) as pb'), 'pb.id_barang', '=', 'barang.id_barang')
            ->where('detail_produk.id_produk', $idProduk)
            ->selectRaw('
                detail_produk.*,
                barang.kode_barang,
                barang.nama_barang,
                barang.satuan_barang,
                (COALESCE(bm.stok_masuk, 0) - COALESCE(bk.stok_keluar_lama, 0) - COALESCE(pb.stok_keluar_resep, 0)) as stok_bahan
            ')
            ->get();
    }
}
