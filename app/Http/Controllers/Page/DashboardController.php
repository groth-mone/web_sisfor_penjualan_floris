<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Page\Barang;
use App\Models\Page\Supplier;
use App\Models\Page\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
	public function dashboard(Request $request)
	{
		$barang = Barang::getBarang($request);
		$supplier = Supplier::count();
		$kategori = Kategori::count();
		return view('page.dashboard.index',compact('barang','supplier','kategori'));
	}
	public function myprofil()
	{
		$data = User::getMyProfil();
		return view('page.myprofil.index',compact('data'));
	}
	public function update_profil(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'name' => 'required',
			'telepon' => 'required'
		];
		$validateMessage += [
			'name.required' => 'Nama harus diisi.',
			'telepon.required' => 'No. HP harus diisi.'
		];
		$validateRules += [
			'email' => 'required',
			'alamat' => 'required'
		];
		$validateMessage += [
			'email.required' => 'Email harus diisi.',
			'alamat.required' => 'Alamat harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		$user = User::join('biodata','biodata.id_user','=','users.id')
		->where('users.id',Auth::user()->id)->first();
		if ($user->email != $request->email) {
			$request->validate([
				'email' => 'unique:users,email'
			],[
				'email.unique' => 'Email yang anda masukkan sudah terdaftar.',
			]);
		}
		try {
			DB::beginTransaction();
			$data = User::where('id',Auth::user()->id)->first();
			$data -> name = $request->name;
			$data -> email = $request->email;
			if ($request->password != '') {
				$data -> password = Hash::make($request->password);
			}
			$data -> save();

			if (!empty($request->file('foto'))) {
				$ambil=$request->file('foto');
				$name=$ambil->getClientOriginalName();
				$namaFileBaru = uniqid();
				$namaFileBaru .= $name;
				$ambil->move(\base_path()."/public/foto_profil", $namaFileBaru);
				$berkas = public_path("foto_profil/".$request->fotoLama);
				File::delete($berkas);
			}else{
				$namaFileBaru = $request->fotoLama;
			}
			$biodata['telepon'] = $request->telepon;
			$biodata['alamat'] = $request->alamat;
			$biodata['foto'] = $namaFileBaru;
			DB::table('biodata')
			->where('id_user', Auth::user()->id)
			->update($biodata);
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Profil berhasil diperbarui !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
}
