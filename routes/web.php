<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CatatanController;
use App\Http\Controllers\AdminCatatanController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserReportController;
use App\Http\Controllers\NoteReportController;
use App\Http\Controllers\AdminNoteReportController;
use App\Http\Controllers\AuthorProfileController;

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

    // Halaman utama diarahkan ke halaman login
    Route::get('/', function () {
        return redirect()->route('login');
    });
});


// ========================================
// HALAMAN MAHASISWA
// ========================================

Route::middleware(['auth', 'role:student', 'no.cache'])->group(function () {

    Route::resource('tugas', TugasController::class)
        ->parameters(['tugas' => 'tugas']);

    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');

    Route::post('/koleksi-catatan', [PlaylistController::class, 'attach'])->name('user.playlists.attach');
    Route::delete('/koleksi/{playlist}/catatan/{catatan}', [PlaylistController::class, 'detach'])
        ->whereNumber('catatan')->name('user.playlists.detach');

    Route::get('/koleksi', [PlaylistController::class, 'index'])
        ->name('user.playlists.index');

    Route::post('/koleksi', [PlaylistController::class, 'store'])
        ->name('user.playlists.store');

    Route::delete('/koleksi/{playlist}', [PlaylistController::class, 'destroy'])
        ->name('user.playlists.destroy');

    Route::put('/koleksi/{playlist}', [PlaylistController::class, 'update'])->name('user.playlists.update');

    Route::get('/catatan-saya',           [CatatanController::class, 'index'])->name('catatan.index');
    Route::get('/unggah-catatan',         [CatatanController::class, 'create'])->name('catatan.create');
    Route::post('/unggah-catatan',        [CatatanController::class, 'store'])->name('catatan.store');
    Route::get('/catatan/{catatan}/edit', [CatatanController::class, 'edit'])->name('catatan.edit');
    Route::put('/catatan/{catatan}',      [CatatanController::class, 'update'])->name('catatan.update');
    Route::delete('/catatan/{catatan}',   [CatatanController::class, 'destroy'])->name('catatan.destroy');

    // Laporan Pengguna oleh Mahasiswa
    Route::post('/user-reports', [UserReportController::class, 'store'])
        ->name('user-reports.store');

    // Profil mahasiswa
    Route::get('/profil', [UserProfileController::class, 'index'])
        ->name('user.profile');

    Route::put('/profil', [UserProfileController::class, 'update'])
        ->name('user.profile.update');

    Route::put('/profil/password', [UserProfileController::class, 'updatePassword'])
        ->name('user.profile.password');

    Route::delete('/profil', [UserProfileController::class, 'destroy'])
        ->name('user.profile.destroy');

    Route::delete('/profil/foto', [UserProfileController::class, 'deletePhoto'])
        ->name('user.profile.photo.delete');

    // Detail Catatan & Laporan
    Route::get('/catatan/{catatan}', [CatatanController::class, 'show'])
        ->name('catatan.show');

    Route::view('/user/reports/preview', 'user.reports.create')
        ->name('user.reports.preview');

    // Buat laporan catatan
    Route::post('/catatan/{catatan}/report', [NoteReportController::class, 'store'])
        ->name('catatan.report.store');

    // Profil penulis catatan
    Route::get('/penulis/{username}', [AuthorProfileController::class, 'show'])
        ->name('user.author-profile');
});


// ========================================
// HALAMAN ADMIN
// ========================================

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin', 'no.cache'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

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

        // CRUD Mata Kuliah
        Route::post('/courses', [CourseController::class, 'store'])
            ->name('courses.store');

        Route::put('/courses/{course}', [CourseController::class, 'update'])
            ->name('courses.update');

        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])
            ->name('courses.destroy');

        // CRUD Kategori
        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');

        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');

        // Kelola Laporan Pengguna oleh Admin
        Route::get('/user-reports', [UserReportController::class, 'index'])
            ->name('user-reports.index');

        Route::patch('/users/{user}/status', [UserReportController::class, 'updateUserStatus'])
            ->name('users.update-status');

        Route::get('/user-reports/{user_report}', [UserReportController::class, 'show'])
            ->name('user-reports.show');

        Route::patch('/user-reports/{user_report}', [UserReportController::class, 'update'])
            ->name('user-reports.update');

        // Profil admin
        Route::get('/profil', [AdminProfileController::class, 'edit'])->name('profil');
        Route::put('/profil', [AdminProfileController::class, 'updateProfil'])->name('profil.update');
        Route::put('/profil/password', [AdminProfileController::class, 'updatePassword'])->name('profil.password');

        // Kelola Catatan
        Route::get('/kelola-catatan', [AdminCatatanController::class, 'index'])
        ->name('kelola-catatan.index');

        Route::get('/kelola-catatan/{id}', [AdminCatatanController::class, 'show'])
        ->name('kelola-catatan.show');

        Route::patch('/kelola-catatan/{id}/restore', [AdminCatatanController::class, 'restore'])
        ->name('kelola-catatan.restore');

        Route::delete('/kelola-catatan/{id}/force-delete', [AdminCatatanController::class, 'forceDelete'])
        ->name('kelola-catatan.force-delete');

        // Kelola Laporan
        Route::get('/kelola-laporan', [AdminNoteReportController::class, 'index'])
            ->name('kelola-laporan');

        Route::patch('/kelola-laporan/{noteReport}/status', [AdminNoteReportController::class, 'updateStatus'])
            ->name('kelola-laporan.updateStatus');

        Route::delete('/kelola-laporan/{noteReport}', [AdminNoteReportController::class, 'destroy'])
            ->name('kelola-laporan.destroy');
    });


// ========================================
// LOGOUT
// ========================================

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
