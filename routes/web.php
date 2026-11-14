<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use GuzzleHttp\Client;

Route::get('/', function () {
    return view('welcome');
});

// Rutas CRUD Categoria
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

// Rutas CRUD Producto
Route::get('/productos', [ProductoController::class, 'index'])
    ->middleware(['auth', 'solo.admin'])
    ->name('productos.index');

Route::get('/productos/crear', [ProductoController::class, 'create'])
    ->middleware(['auth', 'solo.admin'])
    ->name('productos.create');

Route::post('/productos', [ProductoController::class, 'store'])
    ->middleware(['auth', 'solo.admin'])
    ->name('productos.store');

Route::get('/productos/{producto}/editar', [ProductoController::class, 'edit'])
    ->middleware(['auth', 'solo.admin'])
    ->name('productos.edit');

Route::patch('/productos/{producto}', [ProductoController::class, 'update'])
    ->middleware(['auth', 'solo.admin'])
    ->name('productos.update');

Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])
    ->middleware(['auth', 'solo.admin'])
    ->name('productos.destroy');

Route::get('/clientes', [ClienteController::class, 'index'])
    ->middleware(['auth', 'solo.admin'])
    ->name('clientes.index');

Route::get('/clientes/crear', [ClienteController::class, 'create'])
    ->middleware(['auth', 'solo.admin'])
    ->name('clientes.create');

Route::post('/clientes', [ClienteController::class, 'store'])
    ->middleware(['auth', 'solo.admin'])
    ->name('clientes.store');

Route::get('/clientes/{cliente}/editar', [ClienteController::class, 'edit'])
    ->middleware(['auth', 'solo.admin'])
    ->name('clientes.edit');

Route::patch('/clientes/{cliente}', [ClienteController::class, 'update'])
    ->middleware(['auth', 'solo.admin'])
    ->name('clientes.update');

Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])
    ->middleware(['auth', 'solo.admin'])
    ->name('clientes.destroy');