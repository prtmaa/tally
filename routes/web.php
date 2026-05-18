<?php

use App\Http\Controllers\BahanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TanggalBahanController;
use App\Http\Controllers\TanggalController;
use App\Http\Controllers\TimbanganBahanController;
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
    Route::get('/rekap/frozen', [DashboardController::class, 'dataFrozen'])->name('rekap.frozen');
    Route::get('/rekap/fresh', [DashboardController::class, 'dataFresh'])->name('rekap.fresh');
    Route::get('/rekap/bahan', [DashboardController::class, 'dataBahan'])
        ->name('rekap.bahan');
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
    Route::get('/timbangan/rak-list/{id}', function ($id) {
        $data = \App\Models\TujuanProduk::with('timbangans')->findOrFail($id);

        $rak = $data->timbangans
            ->pluck('rak')
            ->filter() // buang null
            ->unique()
            ->sort()
            ->values();

        return response()->json([
            'rak' => $rak
        ]);
    });

    Route::get('/bahan/data', [BahanController::class, 'data'])->name('bahan.data');
    Route::resource('/bahan', BahanController::class);

    Route::get('/tanggalbahan/data', [TanggalBahanController::class, 'data'])->name('tanggalbahan.data');
    Route::resource('/tanggalbahan', TanggalBahanController::class);

    Route::get('/timbangan-bahan/load/{id}', [TimbanganBahanController::class, 'load'])
        ->name('timbanganbahan.load');

    Route::post('/timbangan-bahan', [TimbanganBahanController::class, 'store'])
        ->name('timbanganbahan.store');

    Route::put('/timbangan-bahan/{id}', [TimbanganBahanController::class, 'update']);

    Route::delete('/timbangan-bahan/{id}', [TimbanganBahanController::class, 'destroy'])
        ->name('timbanganbahan.destroy');

    Route::get('/timbanganbahan/{id}', [TimbanganBahanController::class, 'index'])
        ->name('timbanganbahan.index');

    Route::get('/timbangan-bahan/export/{id}', [TimbanganBahanController::class, 'export'])
        ->name('timbanganbahan.export');
});

require __DIR__ . '/auth.php';
