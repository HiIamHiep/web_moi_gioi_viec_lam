<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/test', [TestController::class, 'index'])->name('test');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/registering', [AuthController::class, 'registering'])->name('registering');
Route::get('/', function () {
    return view('layout.master');
})->name('welcome');
Route::get('/auth/redirect/{provider}', function ($provider) {
    return Socialite::driver($provider)->redirect();
})->name('auth.redirect');
Route::get('/auth/callback/{provider}', [AuthController::class, 'callback'])->name('auth.callback');

Route::get('language/{locale?}', function ($locale = null) {
    session()->flush(); // Khử session
    if (in_array($locale, config('app.locales'))) {
        app()->setLocale($locale);
    }

    session()->put('locale', $locale);
    setcookie('locale', $locale, time() + (86400 * 30));

    return redirect()->back();

})->name('language');
