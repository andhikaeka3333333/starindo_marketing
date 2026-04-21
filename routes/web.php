<?php

use App\Http\Controllers\BensinController;
use App\Http\Controllers\BiayaPerjalananController;
use App\Http\Controllers\KategoriPengajuanController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\OmsetController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\TolController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth'])->group(function () {
    Route::resource('marketing', MarketingController::class);

    Route::post('/marketing/store-tarif', [MarketingController::class, 'storeTarif'])->name('marketing.store-tarif');
    Route::post('/marketing/update-tarif', [MarketingController::class, 'updateTarif'])->name('marketing.update-tarif');
    Route::get('/marketing/destroy-tarif/{id}', [MarketingController::class, 'destroyTarif'])->name('marketing.destroy-tarif');

    Route::resource('pengajuan', PengajuanController::class);
    Route::resource('omset', OmsetController::class);
    Route::resource('kategori', KategoriPengajuanController::class);
    Route::resource('bensin', BensinController::class);
    Route::get('/toll', [TolController::class, 'index'])->name('toll.index');
    Route::post('/toll', [TolController::class, 'store'])->name('toll.store');


    Route::prefix('biaya-perjalanan')->group(function () {
        Route::get('/', [BiayaPerjalananController::class, 'index'])->name('biaya-perjalanan.index');
        Route::get('/create', [BiayaPerjalananController::class, 'create'])->name('biaya-perjalanan.create');
        Route::post('/store-temp', [BiayaPerjalananController::class, 'storeTemp'])->name('biaya-perjalanan.storeTemp');
        Route::post('/finalize', [BiayaPerjalananController::class, 'finalize'])->name('biaya-perjalanan.finalize');

        // Edit & Update untuk Data Draf (Temp)
        Route::delete('/temp/{type}/{id}', [BiayaPerjalananController::class, 'destroyTemp'])->name('biaya-perjalanan.destroyTemp');

        Route::get('/edit-temp/{type}/{id}', [BiayaPerjalananController::class, 'editTemp'])->name('biaya-perjalanan.editTemp');
        Route::put('/update-temp/{type}/{id}', [BiayaPerjalananController::class, 'updateTemp'])->name('biaya-perjalanan.updateTemp');

        // Edit & Update untuk Data Resmi (Final)
        Route::get('/{type}/{id}/edit', [BiayaPerjalananController::class, 'edit'])->name('biaya-perjalanan.edit');
        Route::put('/{type}/{id}', [BiayaPerjalananController::class, 'update'])->name('biaya-perjalanan.update');
        Route::delete('/{type}/{id}', [BiayaPerjalananController::class, 'destroy'])->name('biaya-perjalanan.destroy');
    });

    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');



    // Route::prefix('biaya-perjalanan')->name('biaya-perjalanan.')->group(function () {
    //     Route::get('/', [BiayaPerjalananController::class, 'index'])->name('index');
    //     Route::get('/create', [BiayaPerjalananController::class, 'create'])->name('create');
    //     Route::post('/store-temp', [BiayaPerjalananController::class, 'storeTemp'])->name('storeTemp');

    //     // Route untuk Edit DRAF (Yang ada di bawah Form Create)
    //     Route::get('/temp/{id}/edit', [BiayaPerjalananController::class, 'editTemp'])->name('editTemp');
    //     Route::put('/temp/{id}', [BiayaPerjalananController::class, 'updateTemp'])->name('updateTemp');
    //     Route::delete('/temp/{id}', [BiayaPerjalananController::class, 'destroyTemp'])->name('destroyTemp');

    //     // Route untuk Edit Data FINAL (Yang muncul di Index)
    //     Route::get('/{id}/edit', [BiayaPerjalananController::class, 'edit'])->name('edit');
    //     Route::put('/{id}', [BiayaPerjalananController::class, 'update'])->name('update');
    //     Route::delete('/{id}', [BiayaPerjalananController::class, 'destroy'])->name('destroy');
    //     Route::post('/finalize', [BiayaPerjalananController::class, 'finalize'])->name('finalize');
    // });

    // Route::prefix('biaya-perjalanan')->name('biaya-perjalanan.')->group(function () {
    //     Route::get('/', [BiayaPerjalananController::class, 'index'])->name('index');
    //     Route::get('/create', [BiayaPerjalananController::class, 'create'])->name('create');

    //     // Draft (Temp) logic
    //     Route::post('/store-temp', [BiayaPerjalananController::class, 'storeTemp'])->name('storeTemp');
    //     Route::get('/edit-temp/{type}/{id}', [BiayaPerjalananController::class, 'editTemp'])->name('editTemp');
    //     Route::put('/update-temp/{type}/{id}', [BiayaPerjalananController::class, 'updateTemp'])->name('updateTemp');
    //     Route::delete('/destroy-temp/{type}/{id}', [BiayaPerjalananController::class, 'destroyTemp'])->name('destroyTemp');

    //     // Finalizing
    //     Route::post('/finalize', [BiayaPerjalananController::class, 'finalize'])->name('finalize');

    //     // Permanent Data Management
    //     Route::get('/edit/{type}/{id}', [BiayaPerjalananController::class, 'edit'])->name('edit');
    //     Route::put('/update/{type}/{id}', [BiayaPerjalananController::class, 'update'])->name('update');
    //     Route::delete('/destroy/{type}/{id}', [BiayaPerjalananController::class, 'destroy'])->name('destroy');
    // });
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
