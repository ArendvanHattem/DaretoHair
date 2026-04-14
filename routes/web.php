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

Route::get('/team', [TeamController::class, 'index']);

Route::get('/pricelist', [PriceController::class, 'index'])->name('pricelist');

// Auth routes
Route::get('/clientlogin', [loginController::class, 'showLoginForm'])->name('clientlogin');
Route::post('/clientlogin', [loginController::class, 'login'])->name('clientlogin');

Route::get('/adminlogin', [AdminLoginController::class, 'showLoginForm'])->name('adminlogin');
Route::post('/adminlogin', [AdminLoginController::class, 'login'])->name('adminlogin');

Route::get('/clientsignup', [ClientSignupController::class, 'showSignupForm'])->name('clientsignup');
Route::post('/clientsignup', [ClientSignupController::class, 'signup'])->name('clientsignup');

Route::post('/clientlogout', [logoutController::class, 'logout'])->name('clientlogout');

Route::get('/clientdashboard', [ClientDashboardController::class, 'index'])->name('clientdashboard');

// Client agenda routes (beschermd met auth)
Route::middleware(['auth'])->group(function () {
    // Hoofd agenda overzicht
    Route::get('/clientagenda', [ClientAgendaController::class, 'index'])->name('clientagenda');
    
    // Afspraak beheer
    Route::get('/clientagenda/create', [ClientAgendaController::class, 'create'])->name('appointments.create');
    Route::post('/clientagenda', [ClientAgendaController::class, 'store'])->name('appointments.store');
    Route::get('/clientagenda/{id}/edit', [ClientAgendaController::class, 'edit'])->name('appointments.edit');
    Route::put('/clientagenda/{id}', [ClientAgendaController::class, 'update'])->name('appointments.update');
    Route::delete('/clientagenda/{id}', [ClientAgendaController::class, 'destroy'])->name('appointments.destroy');
});