<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Address;
use App\Http\Controllers\API\IndexCepAddressController;

Route::get('cep/{cep}', IndexCepAddressController::class)->name('cep');

Route::prefix('address')->name('addresses.')->group(function () {
    Route::get('/', Address\IndexController::class)->name('index');
    Route::post('/', Address\CreateController::class)->name('store');

    Route::prefix('/{address}')->group(function () {
        Route::patch('/', Address\UpdateController::class)->name('update');
        Route::delete('/', Address\DeleteController::class)->name('delete');
    });
});
