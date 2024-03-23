<?php

use App\Http\Controllers\Applicant\HomePageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', [HomePageController::class, 'index'])->name('welcome');
Route::get('/test', [TestController::class, 'test'])->name('test');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'logining'])->name('logining');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/registering', [AuthController::class, 'registering'])->name('registering');
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
