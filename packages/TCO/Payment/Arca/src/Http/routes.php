<?php

use Illuminate\Support\Facades\Route;
use TCO\Payment\Arca\Http\Controllers\ArcaController;

Route::group(['prefix' => 'arca', 'middleware' => ['web']], function () {
    Route::get('/redirect', [ArcaController::class, 'redirect'])->name('arca.redirect');
    Route::post('/verify', [ArcaController::class, 'verify'])->name('arca.verify');
    Route::get('/success', [ArcaController::class, 'success'])->name('arca.success');
    Route::get('/cancel', [ArcaController::class, 'cancel'])->name('arca.cancel');
});