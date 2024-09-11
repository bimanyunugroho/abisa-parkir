<?php

use App\Http\Controllers\API\ApiParkirController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/transactions/search', [ApiParkirController::class, 'search'])->name('transactions.check');