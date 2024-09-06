<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MonitoringParkingController;
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

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resources([
        'roles' => RoleController::class,
        'parking_areas' => ParkingAreaController::class,
        'parking_rates' => ParkingRateController::class,
        'vehicles' => VehicleController::class,
        'transactions'  => TransactionController::class
    ]);

    Route::get('/monitoring_parkings', [MonitoringParkingController::class, 'index'])->name('monitoring_parkings.index');
});


require __DIR__.'/auth.php';
