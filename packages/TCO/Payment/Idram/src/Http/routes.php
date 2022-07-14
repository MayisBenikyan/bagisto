<?php

use Illuminate\Support\Facades\Route;
use TCO\Payment\Idram\Http\Controllers\IdramController;

Route::group(['prefix' => 'idram', 'middleware' => ['web']], function () {
    Route::get('/redirect', [IdramController::class, 'redirect'])->name('idram.redirect');
    Route::post('/verify', [IdramController::class, 'verify'])->name('idram.verify');
    Route::get('/success', [IdramController::class, 'success'])->name('idram.success');
    Route::get('/cancel', [IdramController::class, 'cancel'])->name('idram.cancel');
});