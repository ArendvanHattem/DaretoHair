<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\auth\loginController;
use App\Http\Controllers\auth\AdminLoginController;
use App\Http\Controllers\auth\ClientSignupController;
use App\Http\Controllers\auth\logoutController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientAgendaController;
use App\Http\Controllers\ClientMakeAppointmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/team', function () {
    return view('team');
});

Route::get('/team', [TeamController::class, 'index']);

Route::get('/pricelist', [PriceController::class, 'index'])->name('pricelist');

Route::get('/clientlogin', [loginController::class, 'showLoginForm'])->name('clientlogin');

Route::get('/adminlogin', [AdminLoginController::class, 'showLoginForm'])->name('adminlogin');

Route::get('/clientsignup', [ClientSignupController::class, 'showSignupForm'])->name('clientsignup');

Route::post('/clientlogin', [loginController::class, 'login'])->name('clientlogin');

Route::post('/adminlogin', [AdminLoginController::class, 'login'])->name('adminlogin');

Route::post('/clientsignup', [ClientSignupController::class, 'signup'])->name('clientsignup');

Route::post('/clientlogout', [logoutController::class, 'logout'])->name('clientlogout');

Route::get('/clientdashboard', [ClientDashboardController::class, 'index'])->name('clientdashboard');

Route::get('/clientagenda', [ClientAgendaController::class, 'index'])->name('clientagenda');

Route::get('/clientmakeappointment', [ClientMakeAppointmentController::class, 'index'])->name('clientmakeappointment');