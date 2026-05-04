<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\RenterController;
use App\Http\Controllers\ViewingsController;

Route::get('/', function () {
    return view('home');
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
    Route::get('/staff/staff-list', [StaffProfileController::class, 'index'])->name('staff.staff');
    Route::get('/staff/properties', [PropertiesController::class, 'index'])->name('staff.properties.properties');
    Route::get('/staff/properties/create', [PropertiesController::class, 'create'])->name('staff.properties.create');
    Route::post('/staff/properties/store', [PropertiesController::class, 'store'])->name('staff.properties.store');
    Route::get('/staff/properties/{id}', [PropertiesController::class, 'showProperty'])->name('staff.properties.show');
    Route::get('/staff/properties/{id}/edit', [PropertiesController::class, 'editProperty'])->name('staff.properties.edit');
    Route::patch('/staff/properties/{id}', [PropertiesController::class, 'update'])->name('staff.properties.update');    
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
    Route::get('/staff/staff-list/{id}', [StaffProfileController::class, 'show'])->name('staff.show');
    Route::get('/staff/staff-list/{id}/edit', [StaffProfileController::class, 'edit'])->name('staff.edit');
    Route::patch('/staff/staff-list/{id}', [StaffProfileController::class, 'update'])->name('staff.update');
    Route::get('/staff/renters', [RenterController::class, 'index'])->name('staff.renters.index');
    Route::get('/staff/renters/create', [RenterController::class, 'create'])->name('staff.renters.create');
    Route::post('/staff/renters/store', [RenterController::class, 'store'])->name('staff.renters.store');
    Route::get('/staff/renters/{id}', [RenterController::class, 'show'])->name('staff.renters.show');
    Route::get('/staff/renters/{id}/edit', [RenterController::class, 'edit'])->name('staff.renters.edit');
    Route::patch('/staff/renters/{id}', [RenterController::class, 'update'])->name('staff.renters.update');
    Route::get('/staff/renters/{id}/leases', [RenterController::class, 'history'])->name('staff.renters.leases');
    Route::get('/staff/viewings', [ViewingsController::class, 'index'])->name('staff.viewings.index');
    Route::get('/staff/viewings/create', [ViewingsController::class, 'create'])->name('staff.viewings.create');
    Route::post('/staff/viewings/store', [ViewingsController::class, 'store'])->name('staff.viewings.store');
    
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
