<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Page\Kategori;
use Illuminate\Support\Facades\Log;
use Exception;

class KategoriController extends Controller
{
	public function index(Request $request)
	{
		$data = Kategori::all();
		return view('page.master_inventaris.kategori.index',compact('data'));
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'nama_kategori' => 'required|unique:kategori,nama_kategori'
		];
		$validateMessage += [
			'nama_kategori.required' => 'Kategori harus diisi.',
			'nama_kategori.unique' => 'Kategori ini sudah ada.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();

			$data = New Kategori();
			$data -> nama_kategori = $request->nama_kategori;
			$data -> created_by = Auth::user()->id;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data Kategori berhasil ditambahkan !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_kategori)
	{
		$data = Kategori::where('id_kategori',$id_kategori)->get();
		return response()->json($data);
	}
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'nama_kategori' => 'required'
		];
		$validateMessage += [
			'nama_kategori.required' => 'Kategori harus diisi.'
		];

		$data = Kategori::where('id_kategori', $request->id_kategori)->first();
		if ($data && $data->nama_kategori !== $request->nama_kategori) {
			$validateRules['nama_kategori'] .= '|unique:kategori,nama_kategori,' . $data->id_kategori . ',id_kategori';
			$validateMessage += [
				'nama_kategori.unique' => 'Kategori ini sudah ada.'
			];
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$data -> nama_kategori = $request->nama_kategori;
			$data -> updated_by = Auth::user()->id;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data Kategori berhasil diubah !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete($id_kategori)
	{
		try {
			DB::beginTransaction();
			$data = Kategori::where('id_kategori',$id_kategori)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data Kategori berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
}
