<?php

use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('welcome');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group([
    'prefix' => 'users',
    'as' => 'users.',
], function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/api', [UserController::class, 'getUserWithAjax'])->name('api');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});
Route::group([
    'prefix' => 'posts',
    'as' => 'posts.',
], function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/create', [PostController::class, 'create'])->name('create');
    Route::post('/store', [PostController::class, 'store'])->name('store');
    Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
    Route::post('/{post}/edit', [PostController::class, 'update'])->name('update');
    Route::post('/import-csv', [PostController::class, 'importCsv'])->name('import_csv');
    Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');

});
Route::group([
    'prefix' => 'companies',
    'as' => 'companies.',
], function () {
    Route::post('/store', [CompanyController::class, 'store'])->name('store');
});

