<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Page\Pembelian;
use App\Models\Page\Supplier;
use App\Models\Page\Barang;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;
use Exception;

class PembelianController extends Controller
{
	public function index(Request $request)
	{
		
		$data = Pembelian::getPembelian($request);
		$barang = Pembelian::getBarang();
		$supplier = Supplier::all();
		return view('page.transaksi.pembelian.index',compact('data','barang','supplier'));
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'tanggal_pembelian' => 'required',
			'id_supplier' => 'required',
			'keterangan_pembelian' => 'required'
		];
		$validateMessage += [
			'tanggal_pembelian.required' => 'Tanggal Pembelian harus diisi.',
			'id_supplier.required' => 'Supplier harus dipilih.',
			'keterangan_pembelian.required' => 'Keterangan harus diisi.'
		];
		if (isset($request->barang)) {
			foreach ($request->barang as $key => $valueb) {
				$validateRules += [
					'barang.*.id_barang' => 'required',
					'barang.*.harga_beli' => 'required',
					'barang.*.harga_jual' => 'required',
					'barang.*.jml_pembelian' => 'required'
				];
				$validateMessage += [
					'barang.*.id_barang.required' => 'Bahan harus dipilih.',
					'barang.*.harga_beli.required' => 'Harga Beli harus diisi.',
					'barang.*.harga_jual.required' => 'Harga Jual harus diisi.',
					'barang.*.jml_pembelian.required' => 'Jumlah Pembelian harus diisi.'
				];
			}
		}
		$request->validate($validateRules, $validateMessage);

		try {
			DB::beginTransaction();
			$last_kode_pembelian = Pembelian::max('kode_pembelian');
			$no_urut = (int) substr($last_kode_pembelian, 3) + 1;
			$new_kode_pembelian = 'PMB' . sprintf("%04d", $no_urut);

			$data = New Pembelian();
			$data -> id_supplier = $request->id_supplier;
			$data -> kode_pembelian = $new_kode_pembelian;
			$data -> tanggal_pembelian = $request->tanggal_pembelian;
			$data -> keterangan_pembelian = $request->keterangan_pembelian;
			$data -> created_by = Auth::user()->id;
			$data -> save();
			if (isset($request->barang)) {
				$idBarangList = array_column($request->barang, 'id_barang');
				$uniqueIdBarangList = array_unique($idBarangList);
				if (count($idBarangList) !== count($uniqueIdBarangList)) {
					return response()->json([
						'status' => 'warning',
						'message' => 'Terdapat duplikasi bahan. Setiap bahan hanya boleh dipilih sekali.',
						'title' => 'Validasi Bahan'
					]);
				}
				foreach ($request->barang as $key => $valueb) {
					$harga_beli = preg_replace("/[^0-9]/", "", $valueb['harga_beli']);
					$harga_jual = preg_replace("/[^0-9]/", "", $valueb['harga_jual']);
					DB::table('pembelian_detail')->insert([
						'id_pembelian' => $data->id_pembelian,
						'id_barang' => $valueb['id_barang'],
						'harga_beli' => $harga_beli,
						'harga_jual' => $harga_jual,
						'jml_pembelian' => $valueb['jml_pembelian']
					]);
				}
			} else {
				return response()->json([
					'status' => 'warning',
					'message' => 'Pembelian bahan harus ditambahkan minimal 1.',
					'title' => 'Pembelian Detail'
				]);
			}
			DB::commit();
			return response()->json(['status'=>'true','message'=>'Pembelian berhasil di tambahkan !!']);
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_pembelian)
	{
		$result = Pembelian::getEdit($id_pembelian);
		$data = $result['data'];
		$pembelian = $result['pembelian'];
		return response()->json(['data'=>$data,'pembelian'=>$pembelian]);
	}
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'tanggal_pembelian' => 'required',
			'id_supplier' => 'required',
			'keterangan_pembelian' => 'required'
		];
		$validateMessage += [
			'tanggal_pembelian.required' => 'Tanggal Pembelian harus diisi.',
			'id_supplier.required' => 'Supplier harus dipilih.',
			'keterangan_pembelian.required' => 'Keterangan harus diisi.'
		];
		if (isset($request->barang)) {
			foreach ($request->barang as $key => $valueb) {
				$validateRules += [
					'barang.*.id_barang' => 'required',
					'barang.*.harga_beli' => 'required',
					'barang.*.harga_jual' => 'required',
					'barang.*.jml_pembelian' => 'required'
				];
				$validateMessage += [
					'barang.*.id_barang.required' => 'Bahan harus dipilih.',
					'barang.*.harga_beli.required' => 'Harga Beli harus diisi.',
					'barang.*.harga_jual.required' => 'Harga Jual harus diisi.',
					'barang.*.jml_pembelian.required' => 'Jumlah Pembelian harus diisi.'
				];
			}
		}
		$request->validate($validateRules, $validateMessage);

		try {
			DB::beginTransaction();

			$data = Pembelian::where('id_pembelian',$request->id_pembelian)->first();
			$data -> id_supplier = $request->id_supplier;
			$data -> tanggal_pembelian = $request->tanggal_pembelian;
			$data -> keterangan_pembelian = $request->keterangan_pembelian;
			$data -> updated_by = Auth::user()->id;
			$data -> save();
			if (!empty($request->id_pembelian_detail_del)) {
				$id_pembelian_detail_del = explode(",", $request->id_pembelian_detail_del);
				DB::table('pembelian_detail')->whereIn('id_pembelian_detail',$id_pembelian_detail_del)->delete();
			}
			if (isset($request->barang)) {
				$idBarangList = array_column($request->barang, 'id_barang');
				$uniqueIdBarangList = array_unique($idBarangList);
				if (count($idBarangList) !== count($uniqueIdBarangList)) {
					return response()->json([
						'status' => 'warning',
						'message' => 'Terdapat duplikasi bahan. Setiap bahan hanya boleh dipilih sekali.',
						'title' => 'Validasi Bahan'
					]);
				}
				foreach ($request->barang as $key => $valueb) {
					$harga_beli = preg_replace("/[^0-9]/", "", $valueb['harga_beli']);
					$harga_jual = preg_replace("/[^0-9]/", "", $valueb['harga_jual']);
					DB::table('pembelian_detail')->updateOrInsert(
						['id_pembelian_detail' => $valueb['id_pembelian_detail'] ?? 0],
						[
							'id_pembelian'   => $data->id_pembelian,
							'id_barang'      => $valueb['id_barang'],
							'harga_beli'     => $harga_beli,
							'harga_jual'     => $harga_jual,
							'jml_pembelian'  => $valueb['jml_pembelian'],
						]
					);
				}
			} else {
				return response()->json([
					'status' => 'warning',
					'message' => 'Pembelian bahan harus ditambahkan minimal 1.',
					'title' => 'Pembelian Detail'
				]);
			}
			DB::commit();
			return response()->json(['status'=>'true','message'=>'Pembelian berhasil di ubah !!']);
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete($id_pembelian)
	{
		try {
			DB::beginTransaction();
			$data = Pembelian::where('id_pembelian',$id_pembelian)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Pembelian berhasil dihapus !!']);
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function laporan(Request $request)
	{
		$data = Pembelian::getLaporan($request);
		return view('page.laporan.pembelian.index',compact('data'));
	}
	public function export(Request $request)
	{
		$data = Pembelian::getLaporan($request);
		$pdf = PDF::loadview('page.laporan.pembelian.export',compact('data'))->setPaper('A4','landscape');
		return $pdf->stream();
	}
}
