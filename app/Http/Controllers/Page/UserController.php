<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
	public function index_pengelola(Request $request)
	{
		$data = User::getUser($request);
		return view('page.master_user.pengelola.index',compact('data'));
	}
	public function save_pengelola(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'name' => 'required',
			'email' => 'required|unique:users,email',
			'password' => 'required',
			'status' => 'required',
			'telepon' => 'required',
			'alamat' => 'required'
		];
		$validateMessage += [
			'name.required' => 'Field Nama harus diisi.',
			'email.required' => 'Field Email harus diisi.',
			'email.unique' => 'Email yang digunakan sudah terdaftar.',
			'password.required' => 'Field Password harus diisi.',
			'status.required' => 'Field Status harus dipilih.',
			'telepon.required' => 'Field Telepon harus diisi.',
			'alamat.required' => 'Field Alamat harus diisi.',
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$user = New user();
			$user -> name = $request->name;
			$user -> email = $request->email;
			$user -> password = hash::make($request->password);
			$user -> level = 'Admin';
			$user -> status = $request->status;
			$user -> save();
			if (!empty($request->file('foto'))) {
				$files = $request->file('foto');
				$foto = $files->getClientOriginalName();
				$namaFileBaru = uniqid();
				$namaFileBaru .= $foto;
				$files->move(\base_path() . "/public/foto", $namaFileBaru);
			}else{
				$namaFileBaru = NULL;
			}
			DB::table('biodata')->insert([
				'id_user'=>$user->id,
				'telepon'=>$request->telepon,
				'foto'=>$namaFileBaru,
				'alamat'=>$request->alamat
			]);
			DB::commit();
			return response()->json(['status'=>'true','message'=>'Data User berhasil ditambahkan !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit_pengelola($id)
	{
		$data = User::getEdit($id);
		return response()->json($data);
	}
	public function update_pengelola(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'name' => 'required',
			'email' => 'required',
			'status' => 'required',
			'telepon' => 'required',
			'alamat' => 'required'
		];
		$validateMessage += [
			'name.required' => 'Field Nama harus diisi.',
			'email.required' => 'Field Email harus diisi.',
			'status.required' => 'Field Status harus dipilih.',
			'telepon.required' => 'Field Telepon harus diisi.',
			'alamat.required' => 'Field Alamat harus diisi.',
		];
		$user = user::where('id',$request->id)->first();
		if ($user->email != $request->email) {
			$request->validate([
				'email' => 'unique:users,email'
			],[
				'email.unique' => 'Email yang anda masukkan sudah terdaftar.',
			]);
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$user -> name = $request->name;
			$user -> email = $request->email;
			if ($request->password != '') {
				$user -> password = hash::make($request->password);
			}
			$user -> status = $request->status;
			$user -> save();
			if (!empty($request->file('foto'))) {
				$files = $request->file('foto');
				$foto = $files->getClientOriginalName();
				$namaFileBaru = uniqid();
				$namaFileBaru .= $foto;
				$files->move(\base_path() . "/public/foto", $namaFileBaru);
			}else{
				$namaFileBaru = $request->fotoLama;
			}
			DB::table('biodata')->where('id_user',$request->id)->update([
				'telepon'=>$request->telepon,
				'foto'=>$namaFileBaru,
				'alamat'=>$request->alamat
			]);
			DB::commit();
			return response()->json(['status'=>'true','message'=>'Data User berhasil diubah !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete_pengelola($id)
	{
		try {
			DB::beginTransaction();
			$data = User::where('id',$id)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true','message'=>'Data User berhasil hapus !!']);
		} catch (Exception $e) {
			DB::rollBack();
		}
	}
}
