<?php

use App\Http\Controllers\Admin\ParkingAreaController;
use App\Http\Controllers\Admin\ParkingRateController;
use App\Http\Controllers\Admin\ParkingSessionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\VehicleController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resources([
        'roles' => RoleController::class,
        'parking_areas' => ParkingAreaController::class,
        'parking_rates' => ParkingRateController::class,
        'parking_sessions'  => ParkingSessionController::class,
        'vehicles' => VehicleController::class,
        'transactions'  => TransactionController::class
    ]);
});


require __DIR__.'/auth.php';
