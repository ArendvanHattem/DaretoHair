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


// --- PUBLIEKE ROUTES ---
// Groepeer algemene pagina's bij één controller
Route::get('/', [HomeController::class, 'index']);
Route::get('/team', [TeamController::class, 'index']);
Route::get('/over-ons', function () { return view('over-ons');});
Route::get('/prijzen', [PriceController::class, 'index'])->name('prijzen');

// --- AUTHENTICATIE ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('loginShowForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/register', [SignupController::class, 'showSignupForm'])->name('register');
Route::post('/register', [SignupController::class, 'signup'])->name('register');

Route::get('/reset-password', [PasswordResetFormController::class, 'index'])->name('passwordresetform');
Route::get('/passwordreset', [PasswordResetController::class, 'index'])->name('passwordreset');

Route::post('/reset-password', [PasswordResetFormController::class, 'handle'])->name('passwordresetform');
Route::post('/passwordreset', [PasswordResetController::class, 'handle'])->name('passwordreset');

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');


// --- ADMIN (MEDEWERKERS) ---
// Prefix 'portal' of 'account' zorgt voor scheiding van de publieke site
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('medewerkers', EmployeeController::class);
    Route::resource('klanten', CustomerController::class);
});



Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/clientagenda', [ClientAgendaController::class, 'index'])->name('clientagenda');

Route::get('/clientmakeappointment', [ClientMakeAppointmentController::class, 'index'])->name('clientmakeappointment');
