<?php

use App\Http\Controllers\AuthController;
use App\Models\Company;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'login']);
Route::get('/', function () {
    return view('layout.master');
});
