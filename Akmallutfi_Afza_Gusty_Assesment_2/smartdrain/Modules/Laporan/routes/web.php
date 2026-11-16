<?php

use Illuminate\Support\Facades\Route;
use Modules\Laporan\Http\Controllers\LaporanController;

Route::middleware(['auth', 'role:admin, warga'])->group(function () {
    Route::resource('laporans', LaporanController::class)->names('laporan');
});
