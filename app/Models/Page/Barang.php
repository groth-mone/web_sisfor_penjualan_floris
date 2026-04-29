<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Barang extends Model
{
    // use HasFactory;
	protected $table="barang";
	protected $primaryKey="id_barang";
    private const KODE_PREFIX = 'BHN';
    private const KODE_LENGTH = 3;

	public static function getBarang($request)
	{
		$data = Barang::query()
			->leftJoin('kategori', 'kategori.id_kategori', '=', 'barang.id_kategori')
			->leftJoin(DB::raw('(SELECT id_barang, SUM(jml_pembelian) as stok_masuk FROM pembelian_detail GROUP BY id_barang) as bm'), 'bm.id_barang', '=', 'barang.id_barang')
			->leftJoin(DB::raw('(SELECT id_barang, SUM(jml_penjualan) as stok_keluar_lama FROM penjualan_detail WHERE id_produk IS NULL GROUP BY id_barang) as bk'), 'bk.id_barang', '=', 'barang.id_barang')
			->leftJoin(DB::raw('(SELECT id_barang, SUM(qty_pakai) as stok_keluar_resep FROM pemakaian_bahan GROUP BY id_barang) as pb'), 'pb.id_barang', '=', 'barang.id_barang')
			->leftJoin(DB::raw('(SELECT id_barang, MAX(id_pembelian_detail) as latest_detail FROM pembelian_detail GROUP BY id_barang) as latest'), 'latest.id_barang', '=', 'barang.id_barang')
			->leftJoin('pembelian_detail as pd_latest', 'pd_latest.id_pembelian_detail', '=', 'latest.latest_detail')
			->selectRaw('
				barang.id_barang,
				barang.id_kategori,
				barang.kode_barang,
				barang.nama_barang,
				barang.satuan_barang,
				barang.gambar,
				barang.stok_minimum,
				barang.harga_default,
				kategori.nama_kategori,
				COALESCE(bm.stok_masuk, 0) as stok_masuk,
				COALESCE(bk.stok_keluar_lama, 0) as stok_keluar_lama,
				COALESCE(pb.stok_keluar_resep, 0) as stok_keluar_resep,
				COALESCE(pd_latest.harga_beli, barang.harga_default) as harga_terakhir,
				(COALESCE(bm.stok_masuk, 0) - COALESCE(bk.stok_keluar_lama, 0) - COALESCE(pb.stok_keluar_resep, 0)) as stok_sekarang
			');

		if (!empty($request->barang)) {
			$data->where('barang.id_barang', $request->barang);
		}

		return $data->orderBy('barang.kode_barang', 'ASC')->get();
	}

	public static function getStokBahan($idBarang)
	{
		return self::getBarang((object) ['barang' => $idBarang])->first();
	}

	public static function generateKodeBarang()
	{
		$lastCode = self::query()
			->where('kode_barang', 'like', self::KODE_PREFIX . '%')
			->orderByRaw('CAST(SUBSTRING(kode_barang, ' . (strlen(self::KODE_PREFIX) + 1) . ') AS UNSIGNED) DESC')
			->value('kode_barang');

		$nextNumber = 1;
		if ($lastCode && preg_match('/^' . self::KODE_PREFIX . '(\d+)$/', $lastCode, $matches)) {
			$nextNumber = ((int) $matches[1]) + 1;
		}

		return self::KODE_PREFIX . str_pad((string) $nextNumber, self::KODE_LENGTH, '0', STR_PAD_LEFT);
	}

	public static function getLaporanPembelianBarang($request)
	{
		$data = Pembelian::join('pembelian_detail', 'pembelian_detail.id_pembelian', '=', 'pembelian.id_pembelian')
			->leftJoin('barang', 'barang.id_barang', '=', 'pembelian_detail.id_barang')
			->where('pembelian_detail.id_barang', $request->barang)
			->selectRaw("
				pembelian.tanggal_pembelian as tanggal,
				pembelian.kode_pembelian as kode,
				'Pembelian' as jenis,
				pembelian_detail.jml_pembelian as qty_masuk,
				0 as qty_keluar,
				pembelian_detail.harga_beli as harga,
				(pembelian_detail.jml_pembelian * pembelian_detail.harga_beli) as total
			");
		if (!empty($request->tanggal_awal) && !empty($request->tanggal_akhir)) {
			$data->whereBetween('pembelian.tanggal_pembelian', [$request->tanggal_awal, $request->tanggal_akhir]);
		}
		return $data->get();
	}

	public static function getLaporanPenjualanBarang($request)
	{
		$penjualanLama = Penjualan::join('penjualan_detail', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
			->where('penjualan_detail.id_barang', $request->barang)
			->whereNull('penjualan_detail.id_produk')
			->selectRaw("
				penjualan.tanggal_penjualan as tanggal,
				penjualan.kode_penjualan as kode,
				'Penjualan Lama' as jenis,
				0 as qty_masuk,
				penjualan_detail.jml_penjualan as qty_keluar,
				penjualan_detail.harga_penjualan as harga,
				(penjualan_detail.jml_penjualan * penjualan_detail.harga_penjualan) as total
			");

		$pemakaianBahan = DB::table('pemakaian_bahan')
			->join('penjualan_detail', 'penjualan_detail.id_penjualan_detail', '=', 'pemakaian_bahan.id_penjualan_detail')
			->join('penjualan', 'penjualan.id_penjualan', '=', 'penjualan_detail.id_penjualan')
			->where('pemakaian_bahan.id_barang', $request->barang)
			->selectRaw("
				penjualan.tanggal_penjualan as tanggal,
				penjualan.kode_penjualan as kode,
				'Pemakaian Bahan' as jenis,
				0 as qty_masuk,
				pemakaian_bahan.qty_pakai as qty_keluar,
				0 as harga,
				0 as total
			");

		if (!empty($request->tanggal_awal) && !empty($request->tanggal_akhir)) {
			$penjualanLama->whereBetween('penjualan.tanggal_penjualan', [$request->tanggal_awal, $request->tanggal_akhir]);
			$pemakaianBahan->whereBetween('penjualan.tanggal_penjualan', [$request->tanggal_awal, $request->tanggal_akhir]);
		}

		return collect($penjualanLama->get())
			->merge($pemakaianBahan->get())
			->values();
	}
}
