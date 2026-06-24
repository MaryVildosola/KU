<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\ProfileController;
use App\Services\WindowService;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------
// PUBLIC — Landing Page
// -------------------------------------------------------
Route::get('/', function () {
    $windowService = app(WindowService::class);
    $activeWindow  = $windowService->getActiveWindow();
    $nextWindow    = $windowService->getNextWindow();
    return view('landing', compact('activeWindow', 'nextWindow'));
});

// -------------------------------------------------------
// PUBLIC — Presentation Pitch Deck
// -------------------------------------------------------
Route::view('/presentation', 'presentation');

// -------------------------------------------------------
// AUTH — Redirect after login based on role
// -------------------------------------------------------
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match($user->role) {
        'admin'      => redirect('/admin/dashboard'),
        'faculty'    => redirect('/faculty/dashboard'),
        'researcher' => redirect('/research/dashboard'),
        default      => redirect('/'),
    };
})->middleware(['auth'])->name('dashboard');

// -------------------------------------------------------
// ADMIN ROUTES
// -------------------------------------------------------
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Windows
    Route::get('/windows',              [AdminController::class, 'windows'])->name('windows');
    Route::get('/windows/create',       [AdminController::class, 'createWindow'])->name('windows.create');
    Route::post('/windows',             [AdminController::class, 'storeWindow'])->name('windows.store');
    Route::get('/windows/{window}/edit',[AdminController::class, 'editWindow'])->name('windows.edit');
    Route::put('/windows/{window}',     [AdminController::class, 'updateWindow'])->name('windows.update');
    Route::delete('/windows/{window}',  [AdminController::class, 'destroyWindow'])->name('windows.destroy');
    Route::post('/windows/{window}/trigger', [AdminController::class, 'triggerWindow'])->name('windows.trigger');

    // AI Window Simulator
    Route::get('/simulator',    [AdminController::class, 'simulator'])->name('simulator');
    Route::post('/simulator/run', [AdminController::class, 'simulatorRun'])->name('simulator.run');

    // Analytics & FOMO
    Route::get('/analytics',      [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/fomo',           [AdminController::class, 'fomoDiagnostic'])->name('fomo');
    Route::post('/fomo/run',      [AdminController::class, 'runFomoDiagnostic'])->name('fomo.run');

    // Policies
    Route::get('/policies', [AdminController::class, 'policies'])->name('policies');

    // Accommodations
    Route::get('/accommodations',              [AdminController::class, 'accommodations'])->name('accommodations');
    Route::put('/accommodations/{accommodation}', [AdminController::class, 'updateAccommodation'])->name('accommodations.update');

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
});

// -------------------------------------------------------
// FACULTY ROUTES
// -------------------------------------------------------
Route::prefix('faculty')->middleware(['auth', 'role:faculty'])->name('faculty.')->group(function () {
    Route::get('/dashboard',    [FacultyController::class, 'dashboard'])->name('dashboard');
    Route::get('/shield',       [FacultyController::class, 'shield'])->name('shield');
    Route::post('/shield',      [FacultyController::class, 'updateShield'])->name('shield.update');
    Route::get('/queue',        [FacultyController::class, 'queue'])->name('queue');
    Route::get('/analytics',    [FacultyController::class, 'analytics'])->name('analytics');
    Route::get('/schedule',     [FacultyController::class, 'schedule'])->name('schedule');
});

// -------------------------------------------------------
// RESEARCH ROUTES
// -------------------------------------------------------
Route::prefix('research')->middleware(['auth', 'role:researcher'])->name('research.')->group(function () {
    Route::get('/dashboard',              [ResearchController::class, 'dashboard'])->name('dashboard');
    Route::get('/datasets/{dataset}/download', [ResearchController::class, 'download'])->name('datasets.download');
    Route::get('/apply',                  [ResearchController::class, 'apply'])->name('apply');
    Route::post('/apply',                 [ResearchController::class, 'submitApplication'])->name('apply.submit');
});

// -------------------------------------------------------
// PROFILE (Breeze)
// -------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
