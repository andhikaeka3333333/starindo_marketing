<?php

use App\Http\Controllers\BensinController;
use App\Http\Controllers\BiayaPerjalananController;
use App\Http\Controllers\KategoriPengajuanController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\OmsetController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TolController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {
    Route::resource('marketing', MarketingController::class);
    Route::resource('pengajuan', PengajuanController::class);
    Route::resource('omset', OmsetController::class);
    Route::resource('kategori', KategoriPengajuanController::class);
    Route::resource('bensin', BensinController::class);
    Route::get('/toll', [TolController::class, 'index'])->name('toll.index');
    Route::post('/toll', [TolController::class, 'store'])->name('toll.store');

    Route::prefix('biaya-perjalanan')->name('biaya-perjalanan.')->group(function () {
        Route::get('/', [BiayaPerjalananController::class, 'index'])->name('index');
        Route::get('/create', [BiayaPerjalananController::class, 'create'])->name('create');
        Route::post('/store-temp', [BiayaPerjalananController::class, 'storeTemp'])->name('storeTemp');

        // Route untuk Edit DRAF (Yang ada di bawah Form Create)
        Route::get('/temp/{id}/edit', [BiayaPerjalananController::class, 'editTemp'])->name('editTemp');
        Route::put('/temp/{id}', [BiayaPerjalananController::class, 'updateTemp'])->name('updateTemp');
        Route::delete('/temp/{id}', [BiayaPerjalananController::class, 'destroyTemp'])->name('destroyTemp');

        // Route untuk Edit Data FINAL (Yang muncul di Index)
        Route::get('/{id}/edit', [BiayaPerjalananController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BiayaPerjalananController::class, 'update'])->name('update');
        Route::delete('/{id}', [BiayaPerjalananController::class, 'destroy'])->name('destroy');



        Route::post('/finalize', [BiayaPerjalananController::class, 'finalize'])->name('finalize');
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
