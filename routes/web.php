<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Page\DashboardController;
use App\Http\Controllers\Page\KategoriController;
use App\Http\Controllers\Page\BarangController;
use App\Http\Controllers\Page\ProdukController;
use App\Http\Controllers\Page\SupplierController;
use App\Http\Controllers\Page\PembelianController;
use App\Http\Controllers\Page\PenjualanController;
use App\Http\Controllers\Page\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth', 'ceklevel:Owner,Admin'])->get('/clear', function() {
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
	Artisan::call('config:clear');
	Artisan::call('view:clear');
	Artisan::call('route:clear');
	dd("Sudah Bersih nih!, Silahkan Kembali ke Halaman Utama");
});

Route::get('/', function () {
	return view('login');
})->name('login');
Route::post('login/request',[AuthController::class,'request_login'])->name('request.login');

// 
Route::middleware(['auth'])->prefix('page')->group(function() {
	// dashboard
	Route::get('dashboard',[DashboardController::class,'dashboard'])->name('index.dashboard');
	// master inv / data master
	Route::middleware(['auth', 'ceklevel:Owner,Admin'])->prefix('master_inventaris')->group(function() {
		// kategori
		Route::get('kategori',[KategoriController::class,'index'])->name('index.kategori');
		Route::post('kategori/save',[KategoriController::class,'save'])->name('save.kategori');
		Route::get('kategori/get_edit/{id_kategori}',[KategoriController::class,'get_edit']);
		Route::post('kategori/update',[KategoriController::class,'update'])->name('update.kategori');
		Route::get('kategori/destroy/{id_kategori}',[KategoriController::class,'delete']);
		// barang
		Route::get('barang',[BarangController::class,'index'])->name('index.barang');
		Route::post('barang/save',[BarangController::class,'save'])->name('save.barang');
		Route::get('barang/get_edit/{id_barang}',[BarangController::class,'get_edit']);
		Route::post('barang/update',[BarangController::class,'update'])->name('update.barang');
		Route::get('barang/destroy/{id_barang}',[BarangController::class,'delete']);
		Route::get('produk',[ProdukController::class,'index'])->name('index.produk');
		Route::post('produk/save',[ProdukController::class,'save'])->name('save.produk');
		Route::get('produk/get_edit/{id_produk}',[ProdukController::class,'get_edit']);
		Route::post('produk/update',[ProdukController::class,'update'])->name('update.produk');
		Route::get('produk/destroy/{id_produk}',[ProdukController::class,'delete']);
		// supplier
		Route::get('supplier',[SupplierController::class,'index'])->name('index.supplier');
		Route::post('supplier/save',[SupplierController::class,'save'])->name('save.supplier');
		Route::get('supplier/get_edit/{id_supplier}',[SupplierController::class,'get_edit']);
		Route::post('supplier/update',[SupplierController::class,'update'])->name('update.supplier');
		Route::get('supplier/destroy/{id_supplier}',[SupplierController::class,'delete']);
	});
	// master user / data master
	Route::middleware(['auth', 'ceklevel:Owner,Admin'])->prefix('master_user')->group(function() {
		// user pengelola - admin
		Route::get('pengelola',[UserController::class,'index_pengelola'])->name('index.pengelola');
		Route::post('pengelola/save',[UserController::class,'save_pengelola'])->name('save.pengelola');
		Route::get('pengelola/get_edit/{id}',[UserController::class,'get_edit_pengelola']);
		Route::post('pengelola/update',[UserController::class,'update_pengelola'])->name('update.pengelola');
		Route::get('pengelola/destroy/{id}',[UserController::class,'delete_pengelola']);
		// pelanggan
		Route::get('pelanggan',[UserController::class,'index_pelanggan'])->name('index.pelanggan');
		Route::post('pelanggan/save',[UserController::class,'save_pelanggan'])->name('save.pelanggan');
		Route::get('pelanggan/get_edit/{id_pelanggan}',[UserController::class,'get_edit_pelanggan']);
		Route::post('pelanggan/update',[UserController::class,'update_pelanggan'])->name('update.pelanggan');
		Route::get('pelanggan/destroy/{id_pelanggan}',[UserController::class,'delete_pelanggan']);
	});
	// transaksi / pengelolaan
	Route::middleware(['auth', 'ceklevel:Owner,Admin'])->prefix('transaksi')->group(function() {
		// pembelian
		Route::get('pembelian',[PembelianController::class,'index'])->name('index.pembelian');
		Route::post('pembelian/save',[PembelianController::class,'save'])->name('save.pembelian');
		Route::get('pembelian/get_edit/{id_pembelian}',[PembelianController::class,'get_edit']);
		Route::post('pembelian/update',[PembelianController::class,'update'])->name('update.pembelian');
		Route::get('pembelian/destroy/{id_pembelian}',[PembelianController::class,'delete']);
		// penjualan
		Route::get('penjualan',[PenjualanController::class,'index'])->name('index.penjualan');
		Route::post('penjualan/save',[PenjualanController::class,'save'])->name('save.penjualan');
		Route::get('penjualan/get_edit/{id_penjualan}',[PenjualanController::class,'get_edit']);
		Route::post('penjualan/update',[PenjualanController::class,'update'])->name('update.penjualan');
		Route::get('penjualan/destroy/{id_penjualan}',[PenjualanController::class,'delete']);
		Route::get('penjualan/confirm/{id_penjualan}',[PenjualanController::class,'confirm']);
		Route::get('penjualan/invoice/{id_penjualan}',[PenjualanController::class,'invoice'])->name('invoice.penjualan');
	});
	// laporan
	Route::middleware(['auth', 'ceklevel:Owner,Admin'])->prefix('laporan')->group(function() {
		// pembelian
		Route::get('pembelian',[PembelianController::class,'laporan'])->name('laporan.pembelian');
		Route::get('pembelian/export',[PembelianController::class,'export'])->name('export.pembelian');
		// penjualan
		Route::get('penjualan',[PenjualanController::class,'laporan'])->name('laporan.penjualan');
		Route::get('penjualan/export',[PenjualanController::class,'export'])->name('export.penjualan');
		// barang / FIFO
		Route::get('barang',[BarangController::class,'laporan'])->name('laporan.barang');
		Route::get('barang/export',[BarangController::class,'export'])->name('export.barang');
	});
	// myprofil
	Route::get('myprofil',[DashboardController::class,'myprofil'])->name('myprofil');
	Route::post('myprofil/update',[DashboardController::class,'update_profil'])->name('update_profil');
});

Route::get('back/auth/logout',[AuthController::class,'logout'])->name('logout');
