<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Default home route → redirect to dashboard
Route::get('/', function () {
    $role = auth()->user()->role ?? null;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        default => redirect()->route('login'),
    };
})->middleware('auth');

// Guest routes
Route::middleware('guest.redirect')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

    Route::get('/login', [RegisterController::class, 'showLogin'])->name('login');
    Route::post('/login', [RegisterController::class, 'login'])->name('login.submit');
});
Route::post('logout', [RegisterController::class, 'logout'])->name('logout');
Route::get('/api/cities/{country_id}', [RegisterController::class, 'getCities'])->name('getCities');

// Authenticated routes with auto redirect
Route::middleware(['auth', 'role.redirect'])->group(function () {
    Route::get('/user/dashboard', [RegisterController::class, 'userDashboard'])->name('user.dashboard')->middleware('role:user');
    Route::get('/admin/dashboard', [RegisterController::class, 'adminDashboard'])->name('admin.dashboard')->middleware('role:admin');
    // Route::post('/admin/users/bulk-upload', [RegisterController::class, 'bulkUpload'])->name('admin.users.bulkUpload');

    Route::get('/admin/bulk-uploads', [RegisterController::class, 'bulkUploadPage'])->name('admin.bulk.uploads');
    Route::post('/admin/bulk-uploads', [RegisterController::class, 'bulkUpload'])->name('admin.bulk.uploads.store');
    Route::get('/admin/bulk-uploads/sample', [RegisterController::class, 'downloadSample'])->name('admin.bulk.uploads.sample');


});

