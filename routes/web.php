<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\AdminDashboardController;

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

Route::resource('tugas', TugasController::class)
    ->parameters(['tugas' => 'tugas']);

Route::get('/dashboard', [UserDashboardController::class, 'index'])
    ->name('user.dashboard');
    

    Route::get('/profil', function () {
        return view('user.profile');
    })->name('user.profile');

    Route::get('/koleksi', [PlaylistController::class, 'index'])
        ->name('user.playlists.index');

    Route::post('/koleksi', [PlaylistController::class, 'store'])
        ->name('user.playlists.store');

    Route::delete('/koleksi/{playlist}', [PlaylistController::class, 'destroy'])
        ->name('user.playlists.destroy');
    

});



// ========================================
// HALAMAN ADMIN
// ========================================

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

    });


// ========================================
// LOGOUT
// ========================================

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
