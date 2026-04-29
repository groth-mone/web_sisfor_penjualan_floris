<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Page\Penjualan;
use App\Models\Page\Barang;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;
use Exception;

class PenjualanController extends Controller
{
	public function index(Request $request)
	{
		$data = Penjualan::getPenjualan($request);
		$barang = Penjualan::getBarang();
		return view('page.transaksi.penjualan.index',compact('barang','data'));
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'tanggal_penjualan' => 'required',
			'pelanggan' => 'required',
			'nomor_pelanggan' => 'required'
		];
		$validateMessage += [
			'tanggal_penjualan.required' => 'Tanggal Penjualan harus diisi.',
			'pelanggan.required' => 'Nama Pelanggan harus diisi.',
			'nomor_pelanggan.required' => 'Nomor Pelanggan harus diisi.'
		];
		if (isset($request->penjualan)) {
			foreach ($request->penjualan as $key => $value) {
				$validateRules += [
					'penjualan.*.metode_pembayaran' => 'required',
					'penjualan.*.nominal_pembayaran' => 'required',
					'penjualan.*.tanggal_pembayaran' => 'required'
				];
				$validateMessage += [
					'penjualan.*.metode_pembayaran.required' => 'Metode Pembayaran harus dipilih.',
					'penjualan.*.nominal_pembayaran.required' => 'Nominal Pembayaran harus diisi.',
					'penjualan.*.tanggal_pembayaran.required' => 'Tanggal Pembayaran harus diisi.'
				];
			}
		}
		if (isset($request->barang)) {
			foreach ($request->barang as $key => $valueb) {
				$validateRules += [
					'barang.*.id_barang' => 'required',
					'barang.*.jml_penjualan' => 'required',
					'barang.*.total_harga' => 'required'
				];
				$validateMessage += [
					'barang.*.id_barang.required' => 'Item harus dipilih.',
					'barang.*.jml_penjualan.required' => 'Jumlah harus diisi.',
					'barang.*.total_harga.required' => 'Total Harga harus diisi.'
				];
			}
		}
		$request->validate($validateRules, $validateMessage);

		try {
			DB::beginTransaction();
			$last_kode_penjualan = Penjualan::max('kode_penjualan');
			$no_urut = (int) substr($last_kode_penjualan, 3) + 1;
			$new_kode_penjualan = 'PNJ' . sprintf("%04d", $no_urut);
			// 
			// dd($id_pelanggan);
			$data = New Penjualan();
			$data -> kode_penjualan = $new_kode_penjualan;
			$data -> tanggal_penjualan = $request->tanggal_penjualan;
			$data -> pelanggan = $request->pelanggan;
			$data -> nomor_pelanggan = $request->nomor_pelanggan;
			$data -> keterangan_penjualan = $request->keterangan_penjualan;
			$data -> ongkir = $this->normalizeCurrency($request->ongkir);
			$data -> alamat_pengiriman = $request->alamat_pengiriman;
			$data -> status_pengiriman = $request->status_pengiriman ?? 'Pesanan Masuk';
			$data -> waktu_pesanan_masuk = now();
			$data -> waktu_diproses = ($request->status_pengiriman == 'Diproses' || $request->status_pengiriman == 'Dikirim' || $request->status_pengiriman == 'Selesai') ? now() : null;
			$data -> waktu_dikirim = ($request->status_pengiriman == 'Dikirim' || $request->status_pengiriman == 'Selesai') ? now() : null;
			$data -> waktu_selesai = $request->status_pengiriman == 'Selesai' ? now() : null;
			$data -> foto_pesanan = $this->uploadFotoPesanan($request);
			$data -> catatan_kuitansi = $request->catatan_kuitansi;
			$data -> save();
			if (isset($request->barang)) {
				$duplicateCheck = $this->validateUniqueItems($request->barang);
				if ($duplicateCheck !== true) {
					return $duplicateCheck;
				}
				foreach ($request->barang as $key => $valueb) {
					$this->storePenjualanDetail($data->id_penjualan, $valueb);
				}
			}else{
				return response()->json(['status'=>'warning','message'=>'Detail Penjualan harus ditambahkan minimal 1.','title'=>'Penjualan']);
			}
			if (isset($request->penjualan)) {
				$nul = 0;
				foreach ($request->penjualan as $key => $value) {
					$nominal_pembayaran = preg_replace("/[^aZ0-9]/", "", $value['nominal_pembayaran']);
					if (empty($value['metode_detail'])) {
						$metode_detail = NULL;
					}else{
						$metode_detail = $value['metode_detail'];
					}
					DB::table('penjualan_pembayaran')->insert([
						'id_penjualan'=>$data->id_penjualan,
						'metode_pembayaran'=>$value['metode_pembayaran'],
						'metode_detail'=>$metode_detail,
						'nominal_pembayaran'=>$nominal_pembayaran,
						'tanggal_pembayaran'=>$value['tanggal_pembayaran']
					]);
				}
			}else{
				return response()->json(['status'=>'warning','message'=>'Pembayaran harus ditambahkan minimal 1.','title'=>'Pembayaran']);
			}
			DB::commit();
			return response()->json(['status'=>'true','message'=>'Penjualan berhasil di simpan !!']);
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_penjualan)
	{
		$result = Penjualan::getEditPenjualan($id_penjualan);
		$data = $result['data'];
		$pembayaran = $result['pembayaran'];
		$detail = $result['detail'];
		return response()->json(['data'=>$data,'pembayaran'=>$pembayaran,'detail'=>$detail]);
	}
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'tanggal_penjualan' => 'required',
			'pelanggan' => 'required',
			'nomor_pelanggan' => 'required'
		];
		$validateMessage += [
			'tanggal_penjualan.required' => 'Tanggal Penjualan harus diisi.',
			'pelanggan.required' => 'Nama Pelanggan harus diisi.',
			'nomor_pelanggan.required' => 'Nomor Pelanggan harus diisi.'
		];
		if (isset($request->penjualan)) {
			foreach ($request->penjualan as $key => $value) {
				$validateRules += [
					'penjualan.*.metode_pembayaran' => 'required',
					'penjualan.*.nominal_pembayaran' => 'required',
					'penjualan.*.tanggal_pembayaran' => 'required'
				];
				$validateMessage += [
					'penjualan.*.metode_pembayaran.required' => 'Metode Pembayaran harus dipilih.',
					'penjualan.*.nominal_pembayaran.required' => 'Nominal Pembayaran harus diisi.',
					'penjualan.*.tanggal_pembayaran.required' => 'Tanggal Pembayaran harus diisi.'
				];
			}
		}
		if (isset($request->barang)) {
			foreach ($request->barang as $key => $valueb) {
				$validateRules += [
					'barang.*.id_barang' => 'required',
					'barang.*.jml_penjualan' => 'required',
					'barang.*.total_harga' => 'required'
				];
				$validateMessage += [
					'barang.*.id_barang.required' => 'Item harus dipilih.',
					'barang.*.jml_penjualan.required' => 'Jumlah harus diisi.',
					'barang.*.total_harga.required' => 'Total Harga harus diisi.'
				];
			}
		}
		$request->validate($validateRules, $validateMessage);

		try {
			DB::beginTransaction();
			
			$data = Penjualan::where('id_penjualan',$request->id_penjualan)->first();
			$data -> tanggal_penjualan = $request->tanggal_penjualan;
			$data -> pelanggan = $request->pelanggan;
			$data -> nomor_pelanggan = $request->nomor_pelanggan;
			$data -> keterangan_penjualan = $request->keterangan_penjualan;
			$data -> ongkir = $this->normalizeCurrency($request->ongkir);
			$data -> alamat_pengiriman = $request->alamat_pengiriman;
			$data -> status_pengiriman = $request->status_pengiriman ?? $data->status_pengiriman;
			$data -> waktu_pesanan_masuk = $data->waktu_pesanan_masuk ?? now();
			$data -> waktu_diproses = ($data->status_pengiriman == 'Diproses' || $data->status_pengiriman == 'Dikirim' || $data->status_pengiriman == 'Selesai') ? ($data->waktu_diproses ?? now()) : null;
			$data -> waktu_dikirim = ($data->status_pengiriman == 'Dikirim' || $data->status_pengiriman == 'Selesai') ? ($data->waktu_dikirim ?? now()) : null;
			$data -> waktu_selesai = $data->status_pengiriman == 'Selesai' ? ($data->waktu_selesai ?? now()) : null;
			$data -> foto_pesanan = $this->uploadFotoPesanan($request, $request->foto_pesanan_lama);
			$data -> catatan_kuitansi = $request->catatan_kuitansi;
			$data -> save();
			if (!empty($request->id_penjualan_detail_del)) {
				$id_penjualan_detail_del = explode(",", $request->id_penjualan_detail_del);
				$this->rollbackPenjualanDetails($id_penjualan_detail_del);
			}
			if (isset($request->barang)) {
				$duplicateCheck = $this->validateUniqueItems($request->barang);
				if ($duplicateCheck !== true) {
					return $duplicateCheck;
				}
				foreach ($request->barang as $key => $valueb) {
					if (($valueb['id_penjualan_detail'] ?? '') == '') {
						$this->storePenjualanDetail($data->id_penjualan, $valueb);
					}
				}
			}else{
				return response()->json(['status'=>'warning','message'=>'Detail Penjualan harus ditambahkan minimal 1.','title'=>'Penjualan']);
			}
			// 
			if (!empty($request->id_metode_pembayaran_del)) {
				$id_metode_pembayaran_del = explode(",", $request->id_metode_pembayaran_del);
				DB::table('penjualan_pembayaran')->whereIn('id_penjualan_pembayaran',$id_metode_pembayaran_del)->delete();
			}
			if (isset($request->penjualan)) {
				$nul = 0;
				foreach ($request->penjualan as $key => $value) {
					$nominal_pembayaran = preg_replace("/[^aZ0-9]/", "", $value['nominal_pembayaran']);
					if (empty($value['metode_detail'])) {
						$metode_detail = NULL;
					}else{
						$metode_detail = $value['metode_detail'];
					}
					if ($value['id_penjualan_pembayaran'] == '') {
						DB::table('penjualan_pembayaran')->insert([
							'id_penjualan'=>$data->id_penjualan,
							'metode_pembayaran'=>$value['metode_pembayaran'],
							'metode_detail'=>$metode_detail,
							'nominal_pembayaran'=>$nominal_pembayaran,
							'tanggal_pembayaran'=>$value['tanggal_pembayaran']
						]);
					}else{
						DB::table('penjualan_pembayaran')->where('id_penjualan_pembayaran',$value['id_penjualan_pembayaran'])->update([
							'metode_pembayaran'=>$value['metode_pembayaran'],
							'metode_detail'=>$metode_detail,
							'nominal_pembayaran'=>$nominal_pembayaran,
							'tanggal_pembayaran'=>$value['tanggal_pembayaran']
						]);
					}
				}
			}else{
				return response()->json(['status'=>'warning','message'=>'Pembayaran harus ditambahkan minimal 1.','title'=>'Pembayaran']);
			}
			DB::commit();
			return response()->json(['status'=>'true','message'=>'Penjualan berhasil di ubah !!']);
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete($id_penjualan)
	{
		try {
			DB::beginTransaction();
			$detailIds = DB::table('penjualan_detail')->where('id_penjualan', $id_penjualan)->pluck('id_penjualan_detail')->all();
			$this->rollbackPenjualanDetails($detailIds);
			DB::table('penjualan_pembayaran')->where('id_penjualan', $id_penjualan)->delete();
			$data = Penjualan::where('id_penjualan',$id_penjualan)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true','message'=>'Penjualan berhasil dihapus !!']);
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function confirm($id_penjualan)
	{
		try {
			DB::beginTransaction();
			$data = Penjualan::where('id_penjualan',$id_penjualan)->first();
			$data -> status_penjualan = 'Selesai';
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true','message'=>'Penjualan dikonfirmasi Selesai !!']);
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function laporan(Request $request)
	{
		$data = Penjualan::getLaporan($request);
		$user = Penjualan::getUser($request);
		return view('page.laporan.penjualan.index',compact('data','user'));
	}
	public function export(Request $request)
	{
		$data = Penjualan::getLaporan($request);
		$user = Penjualan::getUser($request);
		$pdf = PDF::loadview('page.laporan.penjualan.export',compact('data','user'))->setPaper('A4','landscape');
		return $pdf->stream();
	}
	public function invoice($id_penjualan)
	{
		$result = Penjualan::getEditPenjualan($id_penjualan);
		$data = $result['data'];
		$pembayaran = $result['pembayaran'];
		$detail = $result['detail'];
		// $pdf = PDF::loadview('page.transaksi.penjualan.invoice', compact('data','detail','pembayaran'))
  //         ->setPaper([0, 0, 300.77, 842], 'portrait');
		$pdf = PDF::loadview('page.transaksi.penjualan.invoice', compact('data','detail','pembayaran'))
          ->setPaper('A4', 'portrait');
		$pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
		return $pdf->stream();
	}

	private function normalizeCurrency($value)
	{
		return (int) preg_replace('/[^0-9]/', '', (string) $value);
	}

	private function uploadFotoPesanan(Request $request, $default = null)
	{
		if (empty($request->file('foto_pesanan'))) {
			return $default;
		}

		$file = $request->file('foto_pesanan');
		$namaFileBaru = date('YmdHis') . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
		$file->move(base_path() . '/public/foto_pesanan', $namaFileBaru);
		return $namaFileBaru;
	}

	private function validateUniqueItems(array $items)
	{
		$idBarangList = array_filter(array_column($items, 'id_barang'));
		$uniqueIdBarangList = array_unique($idBarangList);
		if (count($idBarangList) !== count($uniqueIdBarangList)) {
			return response()->json([
				'status' => 'warning',
				'message' => 'Terdapat duplikasi item. Setiap item hanya boleh dipilih sekali.',
				'title' => 'Validasi Item'
			]);
		}

		return true;
	}

	private function storePenjualanDetail($idPenjualan, array $detail)
	{
		$parsed = Penjualan::parseItemIdentifier($detail['id_barang']);
		$hargaPenjualan = $this->normalizeCurrency($detail['harga_jual'] ?? 0);
		$jumlah = (int) ($detail['jml_penjualan'] ?? 0);

		if ($parsed['type'] === 'produk') {
			$stokProduk = Penjualan::cekStokProduk($parsed['id'], $jumlah);
			if (empty($stokProduk) || !$stokProduk['cukup']) {
				$namaProduk = $stokProduk['produk']->nama_produk ?? 'Produk';
				$kodeProduk = $stokProduk['produk']->kode_produk ?? '-';
				$stokSekarang = $stokProduk['stok_total'] ?? 0;
				throw new \RuntimeException('Stok ' . $namaProduk . ' (' . $kodeProduk . ') kurang dari Jumlah Penjualan, stok saat ini: ' . $stokSekarang);
			}

			$idPenjualanDetail = DB::table('penjualan_detail')->insertGetId([
				'id_penjualan' => $idPenjualan,
				'harga_penjualan' => $hargaPenjualan,
				'id_barang' => null,
				'id_produk' => $parsed['id'],
				'jml_penjualan' => $jumlah,
				'qty_produk_jadi_terpakai' => $stokProduk['qty_produk_jadi_terpakai'],
				'qty_racik' => $stokProduk['qty_racik'],
				'catatan_detail' => $detail['catatan_detail'] ?? null
			]);

			if ($stokProduk['qty_produk_jadi_terpakai'] > 0) {
				DB::table('produk')
					->where('id_produk', $parsed['id'])
					->decrement('stok_produk_jadi', $stokProduk['qty_produk_jadi_terpakai']);
			}

			if ($stokProduk['qty_racik'] > 0) {
				foreach ($stokProduk['recipe'] as $recipe) {
					DB::table('pemakaian_bahan')->insert([
						'id_penjualan_detail' => $idPenjualanDetail,
						'id_barang' => $recipe->id_barang,
						'qty_pakai' => $recipe->jumlah_pakai * $stokProduk['qty_racik'],
						'keterangan' => 'Pemakaian bahan untuk produk ' . $stokProduk['produk']->nama_produk,
						'created_at' => now(),
						'updated_at' => now(),
					]);
				}
			}

			return;
		}

		$cekStok = Penjualan::cekStokBarang($parsed['id']);
		if (!empty($cekStok) && $cekStok->stok_sekarang < $jumlah) {
			throw new \RuntimeException('Stok ' . $cekStok->nama_barang . ' (' . $cekStok->kode_barang . ') kurang dari Jumlah Penjualan, stok saat ini: ' . $cekStok->stok_sekarang);
		}

		DB::table('penjualan_detail')->insert([
			'id_penjualan' => $idPenjualan,
			'harga_penjualan' => $hargaPenjualan,
			'id_barang' => $parsed['id'],
			'id_produk' => null,
			'jml_penjualan' => $jumlah,
			'qty_produk_jadi_terpakai' => 0,
			'qty_racik' => 0,
			'catatan_detail' => $detail['catatan_detail'] ?? null
		]);
	}

	private function rollbackPenjualanDetails(array $detailIds)
	{
		$detailIds = array_filter($detailIds);
		if (empty($detailIds)) {
			return;
		}

		$details = DB::table('penjualan_detail')->whereIn('id_penjualan_detail', $detailIds)->get();
		foreach ($details as $detail) {
			if (!empty($detail->id_produk) && (int) $detail->qty_produk_jadi_terpakai > 0) {
				DB::table('produk')
					->where('id_produk', $detail->id_produk)
					->increment('stok_produk_jadi', (int) $detail->qty_produk_jadi_terpakai);
			}
		}

		DB::table('pemakaian_bahan')->whereIn('id_penjualan_detail', $detailIds)->delete();
		DB::table('penjualan_detail')->whereIn('id_penjualan_detail', $detailIds)->delete();
	}
}
