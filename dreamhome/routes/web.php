<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('dashboard');
});


Route::get('/staff/login', [StaffLoginController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffLoginController::class, 'login']);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth:staff')->group(function () {
    Route::get('/staff/dashboard', [DashboardController::class, 'index'])->name('staff.dashboard');
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
    Route::get('/staff/profile', [StaffProfileController::class, 'edit'])->name('staff.profile.edit');
    Route::patch('/staff/profile', [StaffProfileController::class, 'update'])->name('staff.profile.update');
    Route::get('/staff/create', [StaffProfileController::class, 'create'])->name('staff.create');
    Route::post('/staff/store', [StaffProfileController::class, 'store'])->name('staff.store');

    Route::post('/staff/logout', [StaffLoginController::class, 'logout'])->name('staff.logout');

});

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');
        Route::get('/leases', function () {
        return view('leases');
    })->name('leases');
    Route::get('/viewings', function () {
        return view('viewings');
    })->name('viewings');
});

require __DIR__.'/auth.php';
