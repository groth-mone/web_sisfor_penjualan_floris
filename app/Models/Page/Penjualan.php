<?php

namespace App\Models\Page;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
	protected $table="penjualan";
	protected $primaryKey="id_penjualan";

	public static function getPenjualan($request)
	{
		$data = Penjualan::leftJoin(DB::raw('(
			SELECT 
			penjualan_detail.id_penjualan, 
			SUM(penjualan_detail.harga_penjualan * penjualan_detail.jml_penjualan) as total_penjualan
			FROM penjualan_detail 
			GROUP BY penjualan_detail.id_penjualan
		) as detail'), function ($join) {
			$join->on('detail.id_penjualan', '=', 'penjualan.id_penjualan');
		})
		->leftJoin(DB::raw('(SELECT id_penjualan, SUM(nominal_pembayaran) as total_pembayaran FROM penjualan_pembayaran GROUP BY id_penjualan) as pembayaran'), function ($join) {
			$join->on('pembayaran.id_penjualan', '=', 'penjualan.id_penjualan');
		})
		->selectRaw('
			penjualan.id_penjualan,
			penjualan.kode_penjualan,
			penjualan.tanggal_penjualan,
			penjualan.pelanggan,
			penjualan.nomor_pelanggan,
			penjualan.keterangan_penjualan,
			penjualan.ongkir,
			penjualan.alamat_pengiriman,
			penjualan.status_pengiriman,
			penjualan.foto_pesanan,
			penjualan.catatan_kuitansi,
			COALESCE(detail.total_penjualan, 0) as total_penjualan,
			COALESCE(pembayaran.total_pembayaran, 0) as total_pembayaran
			');
		$data = $data
		->where('penjualan.status_penjualan','Proses')
		->orderBy('penjualan.tanggal_penjualan', 'DESC')->get();
		return $data;
	}
	public static function getBarang()
	{
		$bahan = Barang::getBarang((object) [])->map(function ($item) {
			return (object) [
				'id_barang' => 'barang:' . $item->id_barang,
				'item_type' => 'barang',
				'item_id' => $item->id_barang,
				'nama_barang' => '[Bahan] ' . $item->nama_barang,
				'kode_barang' => $item->kode_barang,
				'satuan_barang' => $item->satuan_barang,
				'harga_jual' => (int) ($item->harga_terakhir ?? $item->harga_default ?? 0),
				'stok_sekarang' => (int) $item->stok_sekarang,
				'stok_label' => (string) $item->stok_sekarang,
			];
		});

		$produk = Produk::getProduk()->map(function ($item) {
			$recipe = Produk::getRecipe($item->id_produk);
			$stokBisaDiracik = self::calculateCraftableQtyFromRecipe($recipe);
			$stokReady = (int) $item->stok_produk_jadi;
			$stokTotal = $stokReady + $stokBisaDiracik;

			return (object) [
				'id_barang' => 'produk:' . $item->id_produk,
				'item_type' => 'produk',
				'item_id' => $item->id_produk,
				'nama_barang' => '[Produk] ' . $item->nama_produk,
				'kode_barang' => $item->kode_produk,
				'satuan_barang' => 'produk',
				'harga_jual' => (int) $item->harga_jual,
				'stok_sekarang' => $stokTotal,
				'stok_label' => $stokTotal . ' (jadi: ' . $stokReady . ', racik: ' . $stokBisaDiracik . ')',
			];
		});

		return $bahan->merge($produk)->sortBy('nama_barang')->values();
	}
	public static function cekStokBarang($id_barang)
	{
		return Barang::getStokBahan($id_barang);
	}
	public static function cekStokProduk($idProduk, $qtyNeeded = 0)
	{
		$produk = Produk::find($idProduk);
		if (empty($produk)) {
			return null;
		}

		$recipe = Produk::getRecipe($idProduk);
		$stokBisaDiracik = self::calculateCraftableQtyFromRecipe($recipe);
		$stokReady = (int) $produk->stok_produk_jadi;
		$stokTotal = $stokReady + $stokBisaDiracik;
		$qtyNeeded = (int) $qtyNeeded;
		$qtyProdukJadiTerpakai = min($stokReady, $qtyNeeded);
		$qtyRacik = max(0, $qtyNeeded - $qtyProdukJadiTerpakai);

		return [
			'produk' => $produk,
			'recipe' => $recipe,
			'stok_produk_jadi' => $stokReady,
			'stok_bisa_diracik' => $stokBisaDiracik,
			'stok_total' => $stokTotal,
			'qty_produk_jadi_terpakai' => $qtyProdukJadiTerpakai,
			'qty_racik' => $qtyRacik,
			'cukup' => $qtyNeeded <= $stokTotal,
		];
	}
	public static function parseItemIdentifier($value)
	{
		$value = (string) $value;
		if (str_starts_with($value, 'produk:')) {
			return ['type' => 'produk', 'id' => (int) str_replace('produk:', '', $value)];
		}
		if (str_starts_with($value, 'barang:')) {
			return ['type' => 'barang', 'id' => (int) str_replace('barang:', '', $value)];
		}
		return ['type' => 'barang', 'id' => (int) $value];
	}
	public static function getEditPenjualan($id_penjualan)
	{
		$data = Penjualan::leftJoin(DB::raw('(
			SELECT 
			penjualan_detail.id_penjualan, 
			SUM(penjualan_detail.harga_penjualan * penjualan_detail.jml_penjualan) as total_penjualan,
			SUM(penjualan_detail.jml_penjualan) AS jumlah_barang_dijual
			FROM penjualan_detail 
			GROUP BY penjualan_detail.id_penjualan
		) as detail'), function ($join) {
			$join->on('detail.id_penjualan', '=', 'penjualan.id_penjualan');
		})
		->leftJoin(DB::raw('(SELECT id_penjualan, SUM(nominal_pembayaran) as total_pembayaran FROM penjualan_pembayaran GROUP BY id_penjualan) as pembayaran'), function ($join) {
			$join->on('pembayaran.id_penjualan', '=', 'penjualan.id_penjualan');
		})
		->selectRaw('
			penjualan.id_penjualan,
			penjualan.kode_penjualan,
			penjualan.tanggal_penjualan,
			penjualan.nomor_pelanggan,
			penjualan.pelanggan,
			penjualan.status_penjualan,
			penjualan.keterangan_penjualan,
			penjualan.ongkir,
			penjualan.alamat_pengiriman,
			penjualan.status_pengiriman,
			penjualan.waktu_pesanan_masuk,
			penjualan.waktu_diproses,
			penjualan.waktu_dikirim,
			penjualan.waktu_selesai,
			penjualan.foto_pesanan,
			penjualan.catatan_kuitansi,
			COALESCE(detail.total_penjualan, 0) as total_penjualan,
			COALESCE(detail.jumlah_barang_dijual, 0) as jumlah_barang_dijual,
			COALESCE(pembayaran.total_pembayaran, 0) as total_pembayaran
			')
		->where('penjualan.id_penjualan',$id_penjualan)
		->get();
		$pembayaran = Penjualan::join('penjualan_pembayaran','penjualan_pembayaran.id_penjualan','=','penjualan.id_penjualan')
		->where('penjualan.id_penjualan',$id_penjualan)
		->orderBy('penjualan_pembayaran.id_penjualan_pembayaran','DESC')
		->get();
		$detail = Penjualan::join('penjualan_detail','penjualan_detail.id_penjualan','=','penjualan.id_penjualan')
		->leftJoin('barang','barang.id_barang','=','penjualan_detail.id_barang')
		->leftJoin('produk','produk.id_produk','=','penjualan_detail.id_produk')
		->leftJoin('kategori','kategori.id_kategori','=','barang.id_kategori')
		->selectRaw("
			penjualan_detail.*,
			COALESCE(barang.id_barang, penjualan_detail.id_barang) as id_barang_lama,
			COALESCE(barang.kode_barang, produk.kode_produk) as kode_barang,
			COALESCE(barang.nama_barang, produk.nama_produk) as nama_barang,
			COALESCE(barang.satuan_barang, 'produk') as satuan_barang,
			CASE 
				WHEN penjualan_detail.id_produk IS NOT NULL THEN CONCAT('produk:', penjualan_detail.id_produk)
				ELSE CONCAT('barang:', penjualan_detail.id_barang)
			END as katalog_id
		")
		->where('penjualan.id_penjualan',$id_penjualan)
		->orderBy('penjualan_detail.id_penjualan_detail','ASC')
		->get();
		return ['data'=>$data,'pembayaran'=>$pembayaran,'detail'=>$detail];
	}
	public static function getLaporan($request)
	{
		$data = Penjualan::leftJoin(DB::raw('(
			SELECT 
			penjualan_detail.id_penjualan, 
			SUM(penjualan_detail.harga_penjualan * penjualan_detail.jml_penjualan) as total_penjualan
			FROM penjualan_detail 
			GROUP BY penjualan_detail.id_penjualan
		) as detail'), function ($join) {
			$join->on('detail.id_penjualan', '=', 'penjualan.id_penjualan');
		})
		->leftJoin(DB::raw('(SELECT id_penjualan, SUM(nominal_pembayaran) as total_pembayaran FROM penjualan_pembayaran GROUP BY id_penjualan) as pembayaran'), function ($join) {
			$join->on('pembayaran.id_penjualan', '=', 'penjualan.id_penjualan');
		})
		->selectRaw('
			penjualan.id_penjualan,
			penjualan.kode_penjualan,
			penjualan.tanggal_penjualan,
			penjualan.pelanggan,
			penjualan.keterangan_penjualan,
			penjualan.ongkir,
			penjualan.alamat_pengiriman,
			penjualan.status_pengiriman,
			COALESCE(detail.total_penjualan, 0) as total_penjualan,
			COALESCE(pembayaran.total_pembayaran, 0) as total_pembayaran
			');
		if (!empty($request->tanggal_awal) AND !empty($request->tanggal_akhir)) {
			$data->whereBetween('penjualan.tanggal_penjualan',[$request->tanggal_awal,$request->tanggal_akhir]);
		}
		if (!empty($request->user)) {
			$data->where('penjualan.created_by',$request->user);
		}
		$data = $data
		->where('penjualan.status_penjualan','Selesai')
		->orderBy('penjualan.tanggal_penjualan', 'DESC')->get();
		return $data;
	}
	public static function getLaporanDetail($id_penjualan)
	{
		$result = Penjualan::where('penjualan.status_penjualan','Selesai')
		->where('penjualan.id_penjualan',$id_penjualan)
		->orderBy('penjualan.tanggal_penjualan', 'DESC');

		$hppBahan = DB::raw('(
			SELECT pd.id_barang, pd.harga_beli
			FROM pembelian_detail pd
			INNER JOIN (
				SELECT id_barang, MAX(id_pembelian_detail) as latest_detail
				FROM pembelian_detail
				GROUP BY id_barang
			) latest ON latest.latest_detail = pd.id_pembelian_detail
		) as pembelian_detail');

		$hppProduk = DB::raw('(
			SELECT 
				dp.id_produk,
				SUM(dp.jumlah_pakai * COALESCE(hpp.harga_beli, 0)) as harga_beli
			FROM detail_produk dp
			LEFT JOIN (
				SELECT pd.id_barang, pd.harga_beli
				FROM pembelian_detail pd
				INNER JOIN (
					SELECT id_barang, MAX(id_pembelian_detail) as latest_detail
					FROM pembelian_detail
					GROUP BY id_barang
				) latest ON latest.latest_detail = pd.id_pembelian_detail
			) as hpp ON hpp.id_barang = dp.id_barang
			GROUP BY dp.id_produk
		) as hpp_produk');

		$detail = (clone $result)
		->join('penjualan_detail','penjualan_detail.id_penjualan','=','penjualan.id_penjualan')
		->leftJoin('barang','barang.id_barang','=','penjualan_detail.id_barang')
		->leftJoin('produk','produk.id_produk','=','penjualan_detail.id_produk')
		->leftJoin($hppBahan, 'pembelian_detail.id_barang', '=', 'barang.id_barang')
		->leftJoin($hppProduk, 'hpp_produk.id_produk', '=', 'produk.id_produk')
		->selectRaw("
			COALESCE(barang.nama_barang, produk.nama_produk) as nama_barang,
			COALESCE(barang.kode_barang, produk.kode_produk) as kode_barang,
			COALESCE(barang.satuan_barang, 'produk') as satuan_barang,
			COALESCE(hpp_produk.harga_beli, pembelian_detail.harga_beli, 0) as harga_beli,
			penjualan_detail.harga_penjualan as harga_jual,
			penjualan_detail.jml_penjualan as jml_penjualan
		")
		->orderBy('penjualan_detail.id_penjualan_detail', 'ASC')
		->get();

		$pembayaran = (clone $result)
		->join('penjualan_pembayaran','penjualan_pembayaran.id_penjualan','=','penjualan.id_penjualan')
		->get();
		return ['detail'=>$detail,'pembayaran'=>$pembayaran];
	}
	private static function calculateCraftableQtyFromRecipe($recipe)
	{
		if (count($recipe) === 0) {
			return 0;
		}

		$possible = [];
		foreach ($recipe as $item) {
			if ((float) $item->jumlah_pakai <= 0) {
				return 0;
			}
			$possible[] = floor(((float) $item->stok_bahan) / ((float) $item->jumlah_pakai));
		}

		return (int) min($possible);
	}
	public static function getUser($request)
    {
        $data = User::join('biodata','biodata.id_user','=','users.id')
        ->where('users.id',$request->id)
        ->where('users.level','Admin')
        ->get();
        return $data;
    }
}
