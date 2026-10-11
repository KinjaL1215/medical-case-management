<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicalCaseController;

// Login routes
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

// Protected routes
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Admin-only create page: keep this before /cases/{medicalCase}
    Route::middleware('admin')->group(function () {
        Route::get('/cases/create', [MedicalCaseController::class, 'create'])
            ->name('cases.create');

        Route::post('/cases', [MedicalCaseController::class, 'store'])
            ->name('cases.store');

        Route::get('/cases/{medicalCase}/edit', [MedicalCaseController::class, 'edit'])
            ->name('cases.edit');

        Route::put('/cases/{medicalCase}', [MedicalCaseController::class, 'update'])
            ->name('cases.update');

        Route::delete('/cases/{medicalCase}', [MedicalCaseController::class, 'destroy'])
            ->name('cases.destroy');
    });

    // Admin and Viewer can view cases
    Route::get('/cases', [MedicalCaseController::class, 'index'])
        ->name('cases.index');

    Route::get('/cases/{medicalCase}', [MedicalCaseController::class, 'show'])
        ->name('cases.show');
});

// Home page
Route::get('/', function () {
    return redirect()->route('cases.index');
});
