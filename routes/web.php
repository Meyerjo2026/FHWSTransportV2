<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! Auth::check()) {
        return redirect('/login');
    }

    return match (Auth::user()->role) {
        'admin' => redirect('/admin'),
        'staff' => redirect('/staff'),
        default => redirect('/student'),
    };
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Student
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/', [StudentController::class, 'create']);
    Route::post('/', [StudentController::class, 'store']);
    Route::get('/mine', [StudentController::class, 'mine']);
});

// Staff
Route::middleware(['auth', 'role:staff'])->prefix('staff')->group(function () {
    Route::get('/', [StaffController::class, 'approve']);
    Route::get('/bulk', [StaffController::class, 'bulkForm']);
    Route::post('/bulk', [StaffController::class, 'bulkUpload']);
});

// Shared approve/reject action (staff + admin)
Route::middleware(['auth', 'role:staff,admin'])
    ->post('/requests/{tripRequest}/status', [StaffController::class, 'setStatus']);

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'consolidate']);
    Route::get('/review', [AdminController::class, 'review']);
    Route::get('/finalise', [AdminController::class, 'finaliseForm']);
    Route::post('/finalise', [AdminController::class, 'finalise']);
    Route::get('/quotes', [AdminController::class, 'quotesIndex']);
    Route::post('/quotes', [AdminController::class, 'generateQuote']);
    Route::get('/quotes/{quote}', [AdminController::class, 'quoteShow']);
});

require __DIR__.'/auth.php';
