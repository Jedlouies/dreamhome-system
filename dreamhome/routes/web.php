<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StaffLoginController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/staff/login', [StaffLoginController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffLoginController::class, 'login']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth:staff')->group(function () {
    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');
    Route::get('/staff/staff-list', function () {
        return view('staff.staff');
    })->name('staff.staff');
    Route::get('/staff/properties', function () {
        return view('staff.properties');
    })->name('staff.properties');
    Route::get('/staff/renters', function () {
        return view('staff.renters');
    })->name('staff.renters');
    Route::get('/staff/viewings', function () {
        return view('staff.viewings');
    })->name('staff.viewings');
    Route::get('/staff/leases', function () {
        return view('staff.leases');
    })->name('staff.leases');
    Route::get('/staff/inspections', function () {
        return view('staff.inspections');
    })->name('staff.inspections');
    Route::get('/staff/reports', function () {
        return view('staff.reports');
    })->name('staff.reports');

    Route::post('/staff/logout', [StaffLoginController::class, 'logout'])->name('staff.logout');

});

require __DIR__.'/auth.php';
