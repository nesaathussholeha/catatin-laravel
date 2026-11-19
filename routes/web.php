
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AdminProfileController;

// ========================================
// AUTHENTICATION (GUEST)
// ========================================

Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register.store');

    Route::get('/', [LoginController::class, 'create'])
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

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Halaman Master Data
        Route::get('/master-data', [DepartmentController::class, 'index'])
            ->name('master-data.index');

        // CRUD Jurusan
        Route::post('/departments', [DepartmentController::class, 'store'])
            ->name('departments.store');

        Route::put('/departments/{department}', [DepartmentController::class, 'update'])
            ->name('departments.update');

        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])
            ->name('departments.destroy');
    });
    Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Profil admin
        Route::get('/profil', [AdminProfileController::class, 'edit'])->name('profil');
        Route::put('/profil', [AdminProfileController::class, 'updateProfil'])->name('profil.update');
        Route::put('/profil/password', [AdminProfileController::class, 'updatePassword'])->name('profil.password');

    });



// ========================================
// LOGOUT
// ========================================

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
