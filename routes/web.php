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

// // ROUTES FOR EMPLOYEES

// // INDEX
// Route::get('/employees', [EditEmployee::class, 'index'])->name('show_employee');

// // EDIT
// Route::get('/employees/{employee}/edit', [EditEmployee::class, 'edit'])->name('edit_employee');

// // UPDATE
// Route::patch('/employees/{employee}', [EditEmployee::class, 'update'])->name('update_employee');

// // DELETE
// Route::delete('/employees/{employee}/delete', [EditEmployee::class, 'delete'])->name('delete_employee');

// // CREATE
// Route::get('/employees/create', function () {
//     return view('admin.employees.create_employee');
// });

// Route::post('/employees/create', [EditEmployee::class, 'create'])->name('create_employee');



// // ROUTES FOR CUSTOMERS
// Route::get('/customers', [EditCustomer::class, 'index'])->name('show_customers');

// // EDIT
// Route::get('/customers/{klant}/edit', [EditCustomer::class, 'edit'])->name('edit_customer');

// // UPDATE
// Route::patch('/customers/{klant}', [EditCustomer::class, 'update'])->name('update_customer');

// // DELETE
// Route::delete('/customers/{klant}/delete', [EditCustomer::class, 'delete'])->name('delete_customer');

// // CREATE
// Route::get('/customers/create', function () {
//     return view('admin.customers.create_klant');
// });

// Route::post('/customers/create', [EditCustomer::class, 'create'])->name('create_customer');