<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MedicalCaseApiController;

// Public login API
Route::post('/login', [AuthController::class, 'login']);

// Protected APIs
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin and Viewer can view cases
    Route::get('/cases', [MedicalCaseApiController::class, 'index']);
    Route::get('/cases/{id}', [MedicalCaseApiController::class, 'show']);

    // Only Admin can create, update, or delete cases
    Route::middleware('admin')->group(function () {
        Route::post('/cases', [MedicalCaseApiController::class, 'store']);
        Route::put('/cases/{id}', [MedicalCaseApiController::class, 'update']);
        Route::delete('/cases/{id}', [MedicalCaseApiController::class, 'destroy']);
    });

    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    });
});
