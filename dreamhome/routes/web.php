<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeasesController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\RenterController;
use App\Http\Controllers\ViewingsController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientViewingsController;
use App\Http\Controllers\StaffLeasesController;
use App\Http\Controllers\ReportController;

// ===== PUBLIC ROUTES =====
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/landing', fn() => redirect()->route('welcome'));

// ===== STAFF AUTH =====
Route::get('/staff/login', [StaffLoginController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffLoginController::class, 'login']);

// ===== STAFF PROTECTED ROUTES =====
Route::middleware('auth:staff')->group(function () {

    Route::get('/staff/dashboard', [DashboardController::class, 'index'])->name('staff.dashboard');
    Route::get('/staff/dashboard/report', [DashboardController::class, 'downloadReport'])
        ->name('staff.dashboard.report');
    Route::post('/staff/viewings/feedback', [DashboardController::class, 'updateViewingFeedback'])
        ->name('staff.viewings.feedback');   

    // Staff list
    Route::get('/staff/staff-list',          [StaffProfileController::class, 'index'])->name('staff.staff');
    Route::get('/staff/profile', [StaffProfileController::class, 'edit'])->name('staff.profile.edit');
    Route::patch('/staff/profile', [StaffProfileController::class, 'update'])->name('staff.profile.update');

    // Admin Staff Management (Accessed via Staff List)
    Route::get('/staff/{id}/edit', [StaffProfileController::class, 'edit'])->name('staff.edit');
    Route::get('/staff/create',              [StaffProfileController::class, 'create'])->name('staff.create');
    Route::post('/staff/store',              [StaffProfileController::class, 'store'])->name('staff.store');
    Route::get('/staff/staff-list/{id}',     [StaffProfileController::class, 'show'])->name('staff.show');
    Route::get('/staff/staff-list/{id}/edit',[StaffProfileController::class, 'edit'])->name('staff.edit');
    Route::patch('/staff/staff-list/{id}',   [StaffProfileController::class, 'update'])->name('staff.update');
    Route::get('/staff/leases',              [LeasesController::class, 'index'])->name('staff.leases.index');
    // Properties — Issue 2 fix: PropertiesController now imported
    Route::get('/staff/properties',           [PropertiesController::class, 'index'])->name('staff.properties.properties');
    Route::get('/staff/properties/create',    [PropertiesController::class, 'create'])->name('staff.properties.create');
    Route::post('/staff/properties/store',    [PropertiesController::class, 'store'])->name('staff.properties.store');
    Route::get('/staff/properties/{id}',      [PropertiesController::class, 'showProperty'])->name('staff.properties.show');
    Route::get('/staff/properties/{id}/edit', [PropertiesController::class, 'editProperty'])->name('staff.properties.edit');
    Route::patch('/staff/properties/{id}',    [PropertiesController::class, 'update'])->name('staff.properties.update');

    // Renters — Issue 3 fix: now uses RenterController with full CRUD
    Route::get('/staff/renters',              [RenterController::class, 'index'])->name('staff.renters.index');
    Route::get('/staff/renters/create',       [RenterController::class, 'create'])->name('staff.renters.create');
    Route::post('/staff/renters',             [RenterController::class, 'store'])->name('staff.renters.store');
    Route::get('/staff/renters/{id}/leases',  [RenterController::class, 'history'])->name('staff.renters.leases');
    Route::get('/staff/renters/{id}/edit',    [RenterController::class, 'edit'])->name('staff.renters.edit');
    Route::patch('/staff/renters/{id}',       [RenterController::class, 'update'])->name('staff.renters.update');
    Route::get('/staff/renters/{id}',         [RenterController::class, 'show'])->name('staff.renters.show');

    // Viewings — Issue 4 fix: now uses ViewingsController
    Route::get('/staff/viewings',        [ViewingsController::class, 'index'])->name('staff.viewings');
    Route::get('/staff/viewings/create', [ViewingsController::class, 'create'])->name('staff.viewings.create');
    Route::post('/staff/viewings',       [ViewingsController::class, 'store'])->name('staff.viewings.store');
    Route::patch('/staff/viewings/{id}/assign', [ViewingsController::class, 'assign'])->name('staff.viewings.assign');
    Route::get('/staff/viewings/create/{request_id?}', [ViewingsController::class, 'create'])->name('staff.viewings.create');
    Route::get('/staff/viewings/process/{request_id}', [ViewingsController::class, 'processRequest'])
    ->name('staff.viewings.process');

   // Lease Management Routes
    Route::get('/staff/leases', [StaffLeasesController::class, 'index'])->name('staff.leases.index');
    Route::get('/staff/leases/create', [StaffLeasesController::class, 'create'])->name('staff.leases.create');
    Route::post('/staff/leases/store', [StaffLeasesController::class, 'store'])->name('staff.leases.store');
    
    // Detailed View: Shows month-by-month breakdown
    Route::get('/staff/leases/{id}', [StaffLeasesController::class, 'show'])->name('staff.leases.show');
    
    Route::get('/staff/leases/edit/{id}', [StaffLeasesController::class, 'edit'])->name('staff.leases.edit');
    Route::patch('/staff/leases/update/{id}', [StaffLeasesController::class, 'update'])->name('staff.leases.update');

    // Other staff pages
    Route::get('/staff/inspections', fn() => view('staff.inspections'))->name('staff.inspections');
    Route::get('/staff/reports',     fn() => view('staff.reports'))->name('staff.reports');

    Route::get('/staff/reports', [ReportController::class, 'index'])->name('staff.reports');
    Route::get('/staff/reports/generate', [ReportController::class, 'generate'])->name('staff.reports.generate');

    // Staff profile
    Route::get('/staff/profile',    [StaffProfileController::class, 'edit'])->name('staff.profile.edit');
    Route::patch('/staff/profile',  [StaffProfileController::class, 'update'])->name('staff.profile.update');
    Route::post('/staff/logout',    [StaffLoginController::class, 'logout'])->name('staff.logout');
});

// ===== CLIENT AUTH ROUTES =====
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // Issue 8 fix: home now uses HomeController
    Route::get('/home',     [HomeController::class, 'index'])->name('home');

    // Leases
    Route::get('/leases',            [LeasesController::class, 'index'])->name('leases');
    Route::get('/leases/pdf',        [LeasesController::class, 'downloadPdf'])->name('leases.pdf');
    Route::post('/leases/renewal',   [LeasesController::class, 'requestRenewal'])->name('leases.renewal');
    Route::post('/leases/support',   [LeasesController::class, 'contactSupport'])->name('leases.support');
    Route::post('/leases/pay-advance', [LeasesController::class, 'processPayment'])->name('renter.payments.process');

    // Issue 5 & 9 fix: viewings now uses ClientViewingsController
    Route::get('/viewings', [ClientViewingsController::class, 'index'])->name('viewings');
    Route::post('/viewings/book', [ClientViewingsController::class, 'store'])->name('viewings.book');

    ;
});

require __DIR__.'/auth.php';