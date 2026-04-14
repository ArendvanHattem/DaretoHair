<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\SignupController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientAgendaController;
use App\Http\Controllers\ClientMakeAppointmentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\auth\PasswordResetController;
use App\Http\Controllers\auth\PasswordResetFormController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/team', [TeamController::class, 'index']);
Route::get('/over-ons', function () {
    return view('over-ons');
});
Route::get('/prijzen', [PriceController::class, 'index'])->name('prijzen');

// --- AUTHENTICATIE ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('loginShowForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Auth routes
Route::get('/clientlogin', [loginController::class, 'showLoginForm'])->name('clientlogin');
Route::post('/clientlogin', [loginController::class, 'login'])->name('clientlogin');

Route::get('/adminlogin', [AdminLoginController::class, 'showLoginForm'])->name('adminlogin');
Route::post('/adminlogin', [AdminLoginController::class, 'login'])->name('adminlogin');

Route::get('/clientsignup', [ClientSignupController::class, 'showSignupForm'])->name('clientsignup');
Route::post('/clientsignup', [ClientSignupController::class, 'signup'])->name('clientsignup');

// --- ADMIN (MEDEWERKERS) ---
// Prefix 'admin' voor het scheiden van de content voor de klanten
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('medewerkers', EmployeeController::class)->middleware('can:manage employees');
    Route::resource('klanten', CustomerController::class)->middleware('can:manage customers');
    // Deze index wijst nu naar de tabel-weergave in het dashboard
    Route::resource('prijzen', PriceController::class)->middleware('can:manage prices');
});


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
