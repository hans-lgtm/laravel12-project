<?php

use App\Http\Controllers\ElectionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccessCodeController;
use Illuminate\Support\Facades\Route;

// Access Code Routes
Route::get('/access-code', [AccessCodeController::class, 'showVerifyForm'])->name('access-code.verify');
Route::post('/access-code', [AccessCodeController::class, 'verifyCode'])->name('access-code.submit');
Route::post('/logout', [AccessCodeController::class, 'logout'])->name('access-code.logout');

// Public Routes (dilindungi middleware access code)
Route::middleware('access.code')->group(function () {
    Route::get('/', [ElectionController::class, 'index'])->name('election.index');
    Route::get('/candidate/{id}/vision-mission', [ElectionController::class, 'showVisionMission'])->name('candidate.vision-mission');
    Route::post('/vote/{id}', [ElectionController::class, 'vote'])->name('election.vote');
});

// Admin Routes (dilindungi middleware admin)
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/{id}/update', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/{id}/destroy', [AdminController::class, 'destroy'])->name('admin.destroy');
    
    // Route untuk kode akses
    Route::get('/access-codes', [AdminController::class, 'accessCodes'])->name('admin.access-codes');
    Route::get('/access-codes/export', [AdminController::class, 'exportAccessCodes'])->name('admin.access-codes.export');
    Route::post('/access-codes/generate', [AdminController::class, 'generateAccessCodes'])->name('admin.access-codes.generate');
    Route::delete('/access-codes/{id}/delete', [AdminController::class, 'deleteAccessCode'])->name('admin.access-codes.delete');
    Route::post('/access-codes/delete-all', [AdminController::class, 'deleteAllAccessCodes'])->name('admin.access-codes.delete-all');
    Route::post('/access-codes/reset', [AdminController::class, 'resetAllAccessCodes'])->name('admin.access-codes.reset');
    
    // Route untuk statistik
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
});

// Results page HANYA bisa diakses admin
Route::get('/results', [ElectionController::class, 'results'])
    ->middleware('admin.auth')
    ->name('election.results');