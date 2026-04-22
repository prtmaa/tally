<?php

use App\Http\Controllers\BahanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TanggalController;
use App\Http\Controllers\TimbanganController;
use App\Http\Controllers\TujuanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/rekap/data', [DashboardController::class, 'data'])->name('rekap.data');
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::resource('/produk', ProdukController::class);

    Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('/user', UserController::class);

    Route::get('/tally/data', [TanggalController::class, 'data'])->name('tally.data');
    Route::resource('/tally', TanggalController::class);

    Route::get('/tujuan/tanggal/{id}', [TujuanController::class, 'index'])->name('tujuan.index');
    Route::get('/tujuan-data/{id}', [TujuanController::class, 'data'])->name('tujuan.data');
    Route::resource('/tujuan', TujuanController::class)->except(['index']);

    // halaman timbangan per DO
    Route::get('/timbangan/{tujuan}', [TimbanganController::class, 'index'])->name('timbangan.index');

    // load data produk + timbangan
    Route::get('/timbangan/load/{tujuan}', [TimbanganController::class, 'load'])->name('timbangan.load');

    // tambah produk ke DO
    Route::post('/tujuan-produk', [TimbanganController::class, 'storeProduk'])->name('tujuan-produk.store');
    Route::put('/tujuan-produk/{id}', [TimbanganController::class, 'updateProduk']);
    Route::delete('/tujuan-produk/{id}', [TimbanganController::class, 'destroyProduk']);

    // CRUD timbangan
    Route::post('/timbangan', [TimbanganController::class, 'store'])->name('timbangan.store');
    Route::put('/timbangan/{id}', [TimbanganController::class, 'update'])->name('timbangan.update');
    Route::delete('/timbangan/{id}', [TimbanganController::class, 'destroy'])->name('timbangan.destroy');

    Route::get('/timbangan/rekap/{tujuan}', [TimbanganController::class, 'rekap']);
    Route::get('/timbangan/export/{tujuan}', [TimbanganController::class, 'export'])
        ->name('timbangan.export');
    Route::get('/print-struk/{id}', [TimbanganController::class, 'printStruk']);
    Route::get('/print-timbangan/{id}', [TimbanganController::class, 'printTimbangan']);
    Route::post('/timbangan/update-warna/{id}', [TimbanganController::class, 'updateWarna']);

    Route::get('/bahan/data', [BahanController::class, 'data'])->name('bahan.data');
    Route::resource('/bahan', BahanController::class);
});

require __DIR__ . '/auth.php';
