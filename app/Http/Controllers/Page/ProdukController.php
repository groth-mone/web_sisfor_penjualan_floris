<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Page\Barang;
use App\Models\Page\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProdukController extends Controller
{
    public function index()
    {
        $data = Produk::getProduk();
        $bahan = Barang::getBarang((object) []);
        $kodeProdukBaru = Produk::generateKodeProduk();
        return view('page.master_inventaris.produk.index', compact('data', 'bahan', 'kodeProdukBaru'));
    }

    public function save(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        try {
            DB::beginTransaction();

            $produk = new Produk();
            $produk->kode_produk = Produk::generateKodeProduk();
            $produk->nama_produk = $request->nama_produk;
            $produk->harga_jual = $this->normalizeCurrency($request->harga_jual);
            $produk->stok_produk_jadi = (int) ($request->stok_produk_jadi ?? 0);
            $produk->deskripsi = $request->deskripsi;
            $produk->gambar = $this->uploadGambar($request);
            $produk->created_by = Auth::user()->id;
            $produk->save();

            $this->syncDetailProduk($produk->id_produk, $request->bahan ?? []);

            DB::commit();
            return response()->json(['status' => 'true', 'message' => 'Data produk berhasil ditambahkan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
        }
    }

    public function get_edit($id_produk)
    {
        return response()->json(Produk::getEdit($id_produk));
    }

    public function update(Request $request)
    {
        $request->validate($this->rules($request->id_produk), $this->messages());

        try {
            DB::beginTransaction();

            $produk = Produk::findOrFail($request->id_produk);
            $produk->kode_produk = $request->kode_produk;
            $produk->nama_produk = $request->nama_produk;
            $produk->harga_jual = $this->normalizeCurrency($request->harga_jual);
            $produk->stok_produk_jadi = (int) ($request->stok_produk_jadi ?? 0);
            $produk->deskripsi = $request->deskripsi;
            $produk->gambar = $this->uploadGambar($request, $produk->gambar);
            $produk->updated_by = Auth::user()->id;
            $produk->save();

            DB::table('detail_produk')->where('id_produk', $produk->id_produk)->delete();
            $this->syncDetailProduk($produk->id_produk, $request->bahan ?? []);

            DB::commit();
            return response()->json(['status' => 'true', 'message' => 'Data produk berhasil diubah.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
        }
    }

    public function delete($id_produk)
    {
        try {
            DB::beginTransaction();
            DB::table('detail_produk')->where('id_produk', $id_produk)->delete();
            Produk::where('id_produk', $id_produk)->delete();
            DB::commit();
            return response()->json(['status' => 'true', 'message' => 'Data produk berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
        }
    }

    private function syncDetailProduk($idProduk, array $bahan)
    {
        $idBarangList = array_filter(array_column($bahan, 'id_barang'));
        if (count($idBarangList) !== count(array_unique($idBarangList))) {
            throw new \RuntimeException('Terdapat duplikasi bahan pada resep produk.');
        }

        foreach ($bahan as $item) {
            if (empty($item['id_barang']) || empty($item['jumlah_pakai'])) {
                continue;
            }

            DB::table('detail_produk')->insert([
                'id_produk' => $idProduk,
                'id_barang' => $item['id_barang'],
                'jumlah_pakai' => $item['jumlah_pakai'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
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
        $file->move(base_path() . '/public/foto_produk', $namaFileBaru);
        return $namaFileBaru;
    }

    private function rules($idProduk = null)
    {
        $namaRule = 'required|unique:produk,nama_produk';
        if (!empty($idProduk)) {
            $namaRule .= ',' . $idProduk . ',id_produk';
        }

        return [
            'kode_produk' => empty($idProduk) ? 'nullable' : 'required|unique:produk,kode_produk,' . $idProduk . ',id_produk',
            'nama_produk' => $namaRule,
            'harga_jual' => 'required',
            'stok_produk_jadi' => 'required|integer|min:0',
            'bahan.*.id_barang' => 'nullable',
            'bahan.*.jumlah_pakai' => 'nullable|numeric|min:0.01',
        ];
    }

    private function messages()
    {
        return [
            'kode_produk.required' => 'Kode produk harus diisi.',
            'kode_produk.unique' => 'Kode produk sudah digunakan.',
            'nama_produk.required' => 'Nama produk harus diisi.',
            'nama_produk.unique' => 'Nama produk sudah digunakan.',
            'harga_jual.required' => 'Harga jual harus diisi.',
            'stok_produk_jadi.required' => 'Stok produk jadi harus diisi.',
            'stok_produk_jadi.integer' => 'Stok produk jadi harus berupa angka.',
            'bahan.*.jumlah_pakai.numeric' => 'Jumlah pakai harus berupa angka.',
            'bahan.*.jumlah_pakai.min' => 'Jumlah pakai minimal 0.01.',
        ];
    }
}
