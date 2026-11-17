
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserDashboardController;

// ========================================
// AUTHENTICATION (GUEST)
// ========================================

Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});


// ========================================
// HALAMAN MAHASISWA
// ========================================

Route::middleware(['auth', 'role:student'])->group(function () {

Route::get('/dashboard', [UserDashboardController::class, 'index'])
    ->name('user.dashboard');

    Route::get('/profil', function () {
        return view('user.profile');
    })->name('user.profile');

});


// ========================================
// HALAMAN ADMIN
// ========================================

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

    });


// ========================================
// LOGOUT
// ========================================

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
