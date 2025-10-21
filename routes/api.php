<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StringController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('strings')->group(function () {
    Route::post('/', [StringController::class, 'store']);
    Route::get('/', [StringController::class, 'index']);
    Route::get('/filter-by-natural-language', [StringController::class, 'filterByNaturalLanguage']);
    Route::get('/{stringValue}', [StringController::class, 'show']);
    Route::delete('/{stringValue}', [StringController::class, 'destroy']);
});

