<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\AdminLoginController;
use App\Http\Controllers\auth\ClientSignupController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientAgendaController;
use App\Http\Controllers\ClientMakeAppointmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\auth\PasswordResetController;
use App\Http\Controllers\auth\PasswordResetFormController;


Route::get('/', [HomeController::class, 'index']);

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

Route::get('/passwordreset', [PasswordResetController::class, 'index'])->name('passwordreset');

Route::post('/passwordreset', [PasswordResetController::class, 'handle'])->name('passwordreset');

Route::get('/reset-password', [PasswordResetFormController::class, 'index'])->name('passwordresetform');

Route::post('/reset-password', [PasswordResetFormController::class, 'handle'])->name('passwordresetform');