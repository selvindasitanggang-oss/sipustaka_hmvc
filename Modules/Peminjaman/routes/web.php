<?php

use Illuminate\Support\Facades\Route;
use Modules\Peminjaman\Http\Controllers\PeminjamanController;

Route::middleware('auth')->prefix('peminjaman')->name('peminjaman.')->group(function () {
    Route::get('/', [PeminjamanController::class, 'index'])->name('index');
    Route::get('/data', [PeminjamanController::class, 'list'])->name('data');
    Route::post('/', [PeminjamanController::class, 'store'])->name('store');
    Route::get('/{peminjaman}', [PeminjamanController::class, 'show'])->name('show');
    Route::put('/{peminjaman}', [PeminjamanController::class, 'update'])->name('update');
    Route::delete('/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('destroy');
});