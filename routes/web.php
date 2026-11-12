<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/panel', function () {
    return view('panel', [
        'nombre' => \Illuminate\Support\Facades\Auth::user()->nombre,
    ]);
})->middleware('auth');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin', function () {
    return 'Bienvenido al área administrativa de LÚMINA.';
})->middleware(['auth', 'solo.admin']);