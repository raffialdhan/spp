<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\PaymentController as StudentPayment;
use App\Http\Controllers\Student\HistoryController as StudentHistory;
use App\Http\Controllers\Student\BillController as StudentBill;
use App\Http\Controllers\Staff\VerificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\FeeController;

// ============================================================
// GUEST ROUTES - Hanya bisa diakses jika BELUM login
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/',      [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/login', [LoginController::class, 'showLoginForm']);
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================
// AUTH ROUTES - Hanya bisa diakses jika sudah login
// ============================================================
Route::middleware('auth')->group(function () {

    // --- Profil ---
    Route::get('/profile',           [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/info',     [ProfileController::class, 'updateInfo'])->name('profile.update.info');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');

    // ----------------------------------------------------------
    // STUDENT / SISWA
    // ----------------------------------------------------------
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('student.dashboard');
    Route::get('/bills',     [StudentBill::class, 'index'])->name('student.bills');
    Route::get('/payment',   [StudentPayment::class, 'index'])->name('student.payment');
    Route::post('/payment',  [StudentPayment::class, 'store'])->name('student.payment.store');
    Route::get('/history',   [StudentHistory::class, 'index'])->name('student.history');

    // ----------------------------------------------------------
    // STAFF / PETUGAS
    // ----------------------------------------------------------
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard',    [StaffDashboard::class, 'index'])->name('dashboard');
        Route::get('/verification',       [VerificationController::class, 'index'])->name('verification');
        Route::patch('/verification/{payment}', [VerificationController::class, 'verify'])->name('verification.verify');
        Route::get('/payment',            fn() => view('staff.payment'))->name('payment');
    });

    // ----------------------------------------------------------
    // ADMIN / ADMINISTRATOR
    // ----------------------------------------------------------
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Users CRUD
        Route::get('/users',                  [UserController::class, 'index'])->name('users');
        Route::post('/users',                 [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',           [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',        [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/toggle',  [UserController::class, 'toggleStatus'])->name('users.toggle');

        // Students CRUD
        Route::get('/students',               [StudentController::class, 'index'])->name('students');
        Route::post('/students',              [StudentController::class, 'store'])->name('students.store');
        Route::put('/students/{student}',     [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}',  [StudentController::class, 'destroy'])->name('students.destroy');

        // Fees CRUD
        Route::get('/fees',                   [FeeController::class, 'index'])->name('fees');
        Route::post('/fees',                  [FeeController::class, 'store'])->name('fees.store');
        Route::put('/fees/{fee}',             [FeeController::class, 'update'])->name('fees.update');
        Route::patch('/fees/{fee}/toggle',    [FeeController::class, 'toggleStatus'])->name('fees.toggle');
    });

});
