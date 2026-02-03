<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\auth\loginController;
use App\Http\Controllers\auth\AdminLoginController;
use App\Http\Controllers\auth\ClientSignupController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/pricelist', [PriceController::class, 'index']);

Route::get('/team', function () {
    return view('team');
});

Route::get('/clientlogin', [loginController::class, 'showLoginForm'])->name('clientlogin');

Route::get('/adminlogin', [AdminLoginController::class, 'showLoginForm'])->name('adminlogin');

Route::get('/clientsignup', [ClientSignupController::class, 'showSignupForm'])->name('clientsignup');