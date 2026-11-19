<?php

use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('carrito')->name('carrito.')->group(function (): void {
    Route::get('/', [CarritoController::class, 'index'])->block(30)->name('index');
    Route::post('/', [CarritoController::class, 'store'])->block(30)->name('store');
    Route::patch('/{producto}', [CarritoController::class, 'update'])->whereNumber('producto')->block(30)->name('update');
    Route::delete('/{producto}', [CarritoController::class, 'destroy'])->whereNumber('producto')->block(30)->name('destroy');
});

Route::prefix('checkout')->name('checkout.')->group(function (): void {
    Route::get('/', [CheckoutController::class, 'index'])->block(30)->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->block(30)->name('store');
    Route::get('/confirmacion', [CheckoutController::class, 'confirmacion'])->block(30)->name('confirmacion');
});
