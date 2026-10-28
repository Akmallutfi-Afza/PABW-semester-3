<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Form pelaporan warga dapat diakses tanpa login.
Route::resource('laporan', LaporanController::class)->only(['create', 'store']);

// Seluruh halaman pengelolaan laporan hanya untuk petugas yang sudah login.
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('laporan.index');
    })->name('dashboard');

    Route::resource('laporan', LaporanController::class)->except(['create', 'store']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
