<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\MagazineController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [TestController::class, 'index']);
Route::post('/', [TestController::class, 'test']);

Route::name('author.')->middleware(['json'])->group(function () {
    Route::get('/author/list/', [AuthorController::class, 'list'])->name('list');
    Route::post('/author/add/', [AuthorController::class, 'add'])->name('add');
    Route::post('/author/update/', [AuthorController::class, 'update'])->name('update');
    Route::post('/author/delete/', [AuthorController::class, 'delete'])->name('delete');
});

Route::name('magazine.')->middleware(['json'])->group(function () {
    Route::get('/magazine/list/', [MagazineController::class, 'list'])->name('list');
    Route::post('/magazine/add/', [MagazineController::class, 'add'])->name('add');
    Route::post('/magazine/update/', [MagazineController::class, 'update'])->name('update');
    Route::post('/magazine/delete/', [MagazineController::class, 'delete'])->name('delete');
});