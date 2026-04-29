<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Page\Supplier;
use Illuminate\Support\Facades\Log;
use Exception;

class SupplierController extends Controller
{
	public function index(Request $request)
	{
		$data = Supplier::all();
		return view('page.master_inventaris.supplier.index',compact('data'));
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'nama_supplier' => 'required',
			'telepon_supplier' => 'required',
			'alamat_supplier' => 'required'
		];
		$validateMessage += [
			'nama_supplier.required' => 'Nama Supplier harus dipilih.',
			'telepon_supplier.required' => 'Telepon Supplier harus diisi.',
			'alamat_supplier.required' => 'Alamat Supplier harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();

			$data = New Supplier();
			$data -> nama_supplier = $request->nama_supplier;
			$data -> telepon_supplier = $request->telepon_supplier;
			$data -> alamat_supplier = $request->alamat_supplier;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data Supplier berhasil ditambahkan !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_supplier)
	{
		$data = Supplier::where('id_supplier',$id_supplier)->get();
		return response()->json($data);
	}
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'nama_supplier' => 'required',
			'telepon_supplier' => 'required',
			'alamat_supplier' => 'required'
		];
		$validateMessage += [
			'nama_supplier.required' => 'Nama Supplier harus dipilih.',
			'telepon_supplier.required' => 'Telepon Supplier harus diisi.',
			'alamat_supplier.required' => 'Alamat Supplier harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$data = Supplier::where('id_supplier', $request->id_supplier)->first();
			$data -> nama_supplier = $request->nama_supplier;
			$data -> telepon_supplier = $request->telepon_supplier;
			$data -> alamat_supplier = $request->alamat_supplier;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data Supplier berhasil diubah !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete($id_supplier)
	{
		try {
			DB::beginTransaction();
			$data = Supplier::where('id_supplier',$id_supplier)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data Supplier berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
}
