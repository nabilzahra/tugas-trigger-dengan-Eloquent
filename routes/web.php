<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StokinController;
use App\Http\Controllers\StokoutsController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('produk', ProdukController::class)->except(['destroy']);
Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

Route::resource('stokins', StokinController::class)->except(['destroy']);
Route::delete('/stokins/{stokin}', [StokinController::class, 'destroy'])->name('stokins.destroy');

Route::resource('stokouts', StokoutsController::class)->except(['destroy']);
Route::delete('/stokouts/{stokout}', [StokoutsController::class, 'destroy'])->name('stokouts.destroy');
 
Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
