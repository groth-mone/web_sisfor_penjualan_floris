<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pembelian extends Model
{
    // use HasFactory;
	protected $table="pembelian";
	protected $primaryKey="id_pembelian";

	public static function getPembelian($request)
	{
		$data = Pembelian::join(DB::raw('(
			SELECT 
			pembelian_detail.id_pembelian, 
			SUM(pembelian_detail.harga_beli * pembelian_detail.jml_pembelian) as total_pembelian,
			SUM(pembelian_detail.jml_pembelian) AS jumlah_barang_dibeli
			FROM pembelian_detail 
			JOIN barang ON barang.id_barang = pembelian_detail.id_barang
			GROUP BY pembelian_detail.id_pembelian
		) as detail'), function ($join) {
			$join->on('detail.id_pembelian', '=', 'pembelian.id_pembelian');
		})
		->leftJoin('supplier','supplier.id_supplier','=','pembelian.id_supplier')
		->selectRaw('
			pembelian.id_pembelian,
			pembelian.kode_pembelian,
			pembelian.tanggal_pembelian,
			pembelian.keterangan_pembelian,
			supplier.nama_supplier as nama_supplier,
			COALESCE(detail.total_pembelian, 0) as total_pembelian,
			COALESCE(detail.jumlah_barang_dibeli, 0) as jumlah_barang_dibeli
			')
		->orderBy('pembelian.tanggal_pembelian','DESC')
		->get();
		return $data;
	}
	public static function getBarang()
	{
		$data = Barang::leftJoin('kategori','kategori.id_kategori','=','barang.id_kategori')
		->leftJoin(
			\DB::raw('(SELECT id_barang, SUM(jml_pembelian) as stok_masuk FROM pembelian_detail GROUP BY id_barang) as bm'),
			'bm.id_barang', '=', 'barang.id_barang'
		)
		->leftJoin(
			\DB::raw('(SELECT id_barang, SUM(jml_penjualan) as stok_keluar FROM penjualan_detail GROUP BY id_barang) as bk'),
			'bk.id_barang', '=', 'barang.id_barang'
		)
		->select(
			\DB::raw('barang.id_barang as id_barang'),
			\DB::raw('barang.nama_barang as nama_barang'),
			\DB::raw('barang.satuan_barang as satuan_barang'),
			\DB::raw('COALESCE(SUM(bk.stok_keluar), 0) as stok_keluar'),
			\DB::raw('COALESCE(SUM(bm.stok_masuk), 0) as stok_masuk'),
			\DB::RAW('COALESCE(SUM(bm.stok_masuk), 0) - COALESCE(SUM(bk.stok_keluar), 0) as stok_sekarang')
		)
		->groupBy('barang.nama_barang','barang.id_barang','barang.satuan_barang')
		// ->havingRaw('COALESCE(SUM(bm.stok_masuk), 0) - COALESCE(SUM(bk.stok_keluar), 0) > 0')
		->orderBy('barang.kode_barang','ASC')
		->get();
		// dd(count($data));
		return $data;
	}
	public static function getEdit($id_pembelian)
	{
		$result = Pembelian::leftJoin('supplier','supplier.id_supplier','=','pembelian.id_supplier')
		->where('pembelian.id_pembelian',$id_pembelian);
		$data = clone $result;
		$data = $data->get();
		$pembelian = clone $result;
		$pembelian = $pembelian
		->join('pembelian_detail','pembelian_detail.id_pembelian','=','pembelian.id_pembelian')
		->leftJoin('barang','barang.id_barang','=','pembelian_detail.id_barang')
		->get();
		// dd(count($pembelian));
		return ['data'=>$data,'pembelian'=>$pembelian];
	}
	public static function getLaporan($request)
	{
		$data = Pembelian::join(DB::raw('(
			SELECT 
			pembelian_detail.id_pembelian, 
			SUM(pembelian_detail.harga_beli * pembelian_detail.jml_pembelian) as total_pembelian,
			SUM(pembelian_detail.jml_pembelian) AS jumlah_barang_dibeli
			FROM pembelian_detail 
			JOIN barang ON barang.id_barang = pembelian_detail.id_barang
			GROUP BY pembelian_detail.id_pembelian
		) as detail'), function ($join) {
			$join->on('detail.id_pembelian', '=', 'pembelian.id_pembelian');
		})
		->leftJoin('supplier','supplier.id_supplier','=','pembelian.id_supplier')
		->selectRaw('
			pembelian.id_pembelian,
			pembelian.kode_pembelian,
			pembelian.tanggal_pembelian,
			pembelian.keterangan_pembelian,
			supplier.nama_supplier as nama_supplier,
			COALESCE(detail.total_pembelian, 0) as total_pembelian,
			COALESCE(detail.jumlah_barang_dibeli, 0) as jumlah_barang_dibeli
			')
		->orderBy('pembelian.tanggal_pembelian','DESC');
		if (!empty($request->tanggal_awal) AND !empty($request->tanggal_akhir)) {
			$data->whereBetween('pembelian.tanggal_pembelian',[$request->tanggal_awal,$request->tanggal_akhir]);
		}
		$data = $data->get();
		return $data;
	}
	public static function getLaporanDetail($id_pembelian)
	{
		$data = Pembelian::join(DB::raw('(
			SELECT 
			pembelian_detail.id_pembelian, 
			SUM(pembelian_detail.harga_beli * pembelian_detail.jml_pembelian) as total_pembelian,
			SUM(pembelian_detail.jml_pembelian) AS jumlah_barang_dibeli
			FROM pembelian_detail 
			JOIN barang ON barang.id_barang = pembelian_detail.id_barang
			GROUP BY pembelian_detail.id_pembelian
		) as detail'), function ($join) {
			$join->on('detail.id_pembelian', '=', 'pembelian.id_pembelian');
		})
		->leftJoin('supplier','supplier.id_supplier','=','pembelian.id_supplier')
		->join('pembelian_detail','pembelian_detail.id_pembelian','=','pembelian.id_pembelian')
		->leftJoin('barang','barang.id_barang','=','pembelian_detail.id_barang')
		->leftJoin('kategori','kategori.id_kategori','=','barang.id_kategori')
		->where('pembelian.id_pembelian',$id_pembelian)
		->orderBy('pembelian_detail.id_pembelian_detail','DESC');
		if (!empty($request->tanggal_awal) AND !empty($request->tanggal_akhir)) {
			$data->whereBetween('pembelian.tanggal_pembelian',[$request->tanggal_awal,$request->tanggal_akhir]);
		}
		$data = $data->get();
		return $data;
	}
}
