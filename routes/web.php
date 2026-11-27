<?php

use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScholarshipController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/beasiswa');

Route::get('/beasiswa', [ScholarshipController::class, 'index'])->name('scholarships.index');
Route::get('/beasiswa/{scholarship:slug}', [ScholarshipController::class, 'show'])->name('scholarships.show');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    Route::get('/lamaran', [AdminApplicationController::class, 'index'])->name('applications.index');
    Route::patch('/lamaran/{application}', [AdminApplicationController::class, 'update'])->name('applications.update');
    Route::get('/dokumen/{document}', [AdminApplicationController::class, 'downloadDocument'])->name('documents.download');
    Route::get('/beasiswa', [ScholarshipController::class, 'adminIndex'])->name('scholarships.index');
    Route::resource('/beasiswa', ScholarshipController::class)
        ->except(['index', 'show'])
        ->parameters(['beasiswa' => 'scholarship'])
        ->names('scholarships');
});

Route::middleware(['auth', 'student'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/simpanan', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/beasiswa/{scholarship:slug}/simpan', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::get('/lamaran', [ApplicationController::class, 'index'])->name('applications.index');
    Route::post('/beasiswa/{scholarship:slug}/daftar', [ApplicationController::class, 'store'])->name('applications.store');
});

require __DIR__.'/auth.php';
