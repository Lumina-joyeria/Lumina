<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;

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

Route::get('/categorias', [CategoriaController::class, 'index'])
    ->middleware(['auth', 'solo.admin'])
    ->name('categorias.index');

Route::get('/categorias/crear', [CategoriaController::class, 'create'])
    ->middleware(['auth', 'solo.admin'])
    ->name('categorias.create');

Route::post('/categorias', [CategoriaController::class, 'store'])
    ->middleware(['auth', 'solo.admin'])
    ->name('categorias.store');

Route::get('/categorias/{categoria}/editar', [CategoriaController::class, 'edit'])
    ->middleware(['auth', 'solo.admin'])
    ->name('categorias.edit');

Route::patch('/categorias/{categoria}', [CategoriaController::class, 'update'])
    ->middleware(['auth', 'solo.admin'])
    ->name('categorias.update');

Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])
    ->middleware(['auth', 'solo.admin'])
    ->name('categorias.destroy');