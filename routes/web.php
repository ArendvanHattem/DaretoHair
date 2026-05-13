<?php

use App\Http\Controllers\AccountgegevensController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\auth\PasswordResetController;
use App\Http\Controllers\auth\PasswordResetFormController;
use App\Http\Controllers\auth\SignupController;
use App\Http\Controllers\ClientAgendaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// --- PUBLIEKE ROUTES ---
// Groepeer algemene pagina's bij één controller
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/team', [TeamController::class, 'index']);
Route::get('/over-ons', function () {
    return view('over-ons');
});
Route::get('/prijzen', [PriceController::class, 'index'])->name('prijzen');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('loginShowForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/register', [SignupController::class, 'showSignupForm'])->name('register');
Route::post('/register', [SignupController::class, 'signup'])->name('register');

Route::get('/reset-password', [PasswordResetFormController::class, 'index'])->name('passwordresetform');
Route::get('/passwordreset', [PasswordResetController::class, 'index'])->name('passwordreset');

Route::post('/reset-password', [PasswordResetFormController::class, 'handle'])->name('passwordresetform');
Route::post('/passwordreset', [PasswordResetController::class, 'handle'])->name('passwordreset');

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// De publieke route voor iedereen (klanten en medewerkers)
Route::get('/prijzen', [PriceController::class, 'publicIndex'])->name('prijzen.public');

// --- ADMIN (MEDEWERKERS) ---
// Prefix 'admin' voor het scheiden van de content voor de klanten
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('medewerkers', EmployeeController::class)->middleware('can:manage employees');
    Route::resource('klanten', CustomerController::class)->middleware('can:manage customers');
    // Deze index wijst nu naar de tabel-weergave in het dashboard
    Route::resource('prijzen', PriceController::class)->middleware('can:manage prices');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'role:medewerker']);

Route::get('/clientagenda', [ClientAgendaController::class, 'index'])->name('clientagenda')->middleware(['auth', 'role:klant']);

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

    // Account routes (van main)
    Route::get('/account', [AccountgegevensController::class, 'show'])->name('account.show');
    Route::post('/account/update', [AccountgegevensController::class, 'update'])->name('account.update');
    Route::post('/account/password', [AccountgegevensController::class, 'updatePassword'])->name('account.password');
    Route::delete('/account/delete', [AccountgegevensController::class, 'destroy'])->name('account.delete');
});
