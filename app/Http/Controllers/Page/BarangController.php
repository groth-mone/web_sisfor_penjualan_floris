<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Page\Barang;
use App\Models\Page\Kategori;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;
use Exception;

class BarangController extends Controller
{
	public function index(Request $request)
	{
		$data = Barang::getBarang($request);
		$kategori = Kategori::all();
		$kodeBarangBaru = Barang::generateKodeBarang();
		return view('page.master_inventaris.barang.index',compact('data','kategori', 'kodeBarangBaru'));
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'id_kategori' => 'required',
			'nama_barang' => 'required|unique:barang,nama_barang',
			'satuan_barang' => 'required',
			'stok_minimum' => 'nullable|integer|min:0',
			'harga_default' => 'nullable',
		];
		$validateMessage += [
			'id_kategori.required' => 'Kategori harus dipilih.',
			'nama_barang.required' => 'Nama bahan harus diisi.',
			'nama_barang.uniqid' => 'Nama bahan ini sudah ada.',
			'satuan_barang.required' => 'Satuan bahan harus diisi.',
			'stok_minimum.integer' => 'Stok minimum harus berupa angka.',
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();

			$data = New Barang();
			$data -> id_kategori = $request->id_kategori;
			$data -> kode_barang = Barang::generateKodeBarang();
			$data -> nama_barang = $request->nama_barang;
			$data -> satuan_barang = $request->satuan_barang;
			$data -> stok_minimum = (int) ($request->stok_minimum ?? 0);
			$data -> harga_default = $this->normalizeCurrency($request->harga_default);
			$data -> gambar = $this->uploadGambar($request);
			$data -> created_by = Auth::user()->id;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data bahan berhasil ditambahkan !!']);
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_barang)
	{
		$data = Barang::where('id_barang',$id_barang)->get();
		return response()->json($data);
	}
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'id_kategori' => 'required',
			'kode_barang' => 'required',
			'nama_barang' => 'required',
			'satuan_barang' => 'required',
			'stok_minimum' => 'nullable|integer|min:0',
			'harga_default' => 'nullable',
		];
		$validateMessage += [
			'id_kategori.required' => 'Kategori harus dipilih.',
			'kode_barang.required' => 'Kode bahan harus diisi.',
			'nama_barang.required' => 'Nama bahan harus diisi.',
			'satuan_barang.required' => 'Satuan bahan harus diisi.',
			'stok_minimum.integer' => 'Stok minimum harus berupa angka.',
		];

		$data = Barang::where('id_barang', $request->id_barang)->first();
		if ($data && $data->nama_barang !== $request->nama_barang) {
			$validateRules['nama_barang'] .= '|unique:barang,nama_barang,' . $data->id_barang . ',id_barang';
			$validateMessage += [
				'nama_barang.uniqid' => 'Nama bahan ini sudah ada.'
			];
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$data -> id_kategori = $request->id_kategori;
			$data -> kode_barang = $request->kode_barang;
			$data -> nama_barang = $request->nama_barang;
			$data -> satuan_barang = $request->satuan_barang;
			$data -> stok_minimum = (int) ($request->stok_minimum ?? 0);
			$data -> harga_default = $this->normalizeCurrency($request->harga_default);
			$data -> gambar = $this->uploadGambar($request, $request->gambar_lama);
			$data -> updated_by = Auth::user()->id;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data bahan berhasil diubah !!']);
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete($id_barang)
	{
		try {
			DB::beginTransaction();
			$data = Barang::where('id_barang',$id_barang)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data bahan berhasil dihapus !!']);
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function laporan(Request $request)
	{
		$pembelian = Barang::getLaporanPembelianBarang($request);
		$penjualan = Barang::getLaporanPenjualanBarang($request);
		$barang = Barang::getBarang($request);
		$transactions = $this->buildInventoryTransactions($pembelian, $penjualan);

		return view('page.laporan.barang.index', ['combined' => $transactions,'barang'=>$barang]);
	}
	public function export(Request $request)
	{
		$pembelian = Barang::getLaporanPembelianBarang($request);
		$penjualan = Barang::getLaporanPenjualanBarang($request);
		$barang = Barang::getBarang($request);
		$transactions = $this->buildInventoryTransactions($pembelian, $penjualan);

		$pdf = PDF::loadview('page.laporan.barang.export',['combined' => $transactions,'barang'=>$barang])->setPaper('A4','landscape');
		return $pdf->stream();
	}

	private function buildInventoryTransactions($pembelian, $penjualan)
	{
		$transactions = [];

		foreach ($pembelian as $pem) {
			$transactions[] = [
				'tanggal' => $pem->tanggal,
				'kode' => $pem->kode,
				'jenis' => $pem->jenis,
				'qty_pembelian' => $pem->qty_masuk,
				'harga_pembelian' => $pem->harga,
				'total_pembelian' => $pem->total,
				'qty_penjualan' => 0,
				'harga_penjualan' => 0,
				'total_penjualan' => 0,
				'sisa_stok' => 0,
				'harga_persediaan' => 0,
				'total_persediaan' => 0
			];
		}

		foreach ($penjualan as $pen) {
			$transactions[] = [
				'tanggal' => $pen->tanggal,
				'kode' => $pen->kode,
				'jenis' => $pen->jenis,
				'qty_pembelian' => 0,
				'harga_pembelian' => 0,
				'total_pembelian' => 0,
				'qty_penjualan' => $pen->qty_keluar,
				'harga_penjualan' => $pen->harga,
				'total_penjualan' => $pen->total,
				'sisa_stok' => 0,
				'harga_persediaan' => 0,
				'total_persediaan' => 0
			];
		}

		usort($transactions, function($a, $b) {
			return strtotime($a['tanggal']) - strtotime($b['tanggal']);
		});

		$fifoStok = [];
		foreach ($transactions as &$tr) {
			if ($tr['qty_pembelian'] > 0) {
				$fifoStok[] = [
					'qty' => $tr['qty_pembelian'],
					'harga' => $tr['harga_pembelian']
				];
			}

			if ($tr['qty_penjualan'] > 0) {
				$qtyToDeduct = $tr['qty_penjualan'];
				foreach ($fifoStok as &$item) {
					if ($qtyToDeduct == 0) {
						break;
					}
					if ($item['qty'] == 0) {
						continue;
					}
					if ($item['qty'] >= $qtyToDeduct) {
						$item['qty'] -= $qtyToDeduct;
						$qtyToDeduct = 0;
					} else {
						$qtyToDeduct -= $item['qty'];
						$item['qty'] = 0;
					}
				}
				unset($item);
			}

			$totalQty = array_sum(array_column($fifoStok, 'qty'));
			$hargaTerakhir = 0;
			foreach (array_reverse($fifoStok) as $stok) {
				if ($stok['qty'] > 0) {
					$hargaTerakhir = $stok['harga'];
					break;
				}
			}

			$tr['sisa_stok'] = $totalQty;
			$tr['harga_persediaan'] = $hargaTerakhir;
			$tr['total_persediaan'] = $totalQty * $hargaTerakhir;
		}

		return $transactions;
	}

private function normalizeCurrency($value)
{
	return (int) preg_replace('/[^0-9]/', '', (string) $value);
}

private function uploadGambar(Request $request, $default = null)
{
	if (empty($request->file('gambar'))) {
		return $default;
	}

	$file = $request->file('gambar');
	$namaFileBaru = date('YmdHis') . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
	$file->move(base_path() . '/public/foto_bahan', $namaFileBaru);
	return $namaFileBaru;
}

}
