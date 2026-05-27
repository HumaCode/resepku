<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pengguna\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/roles-permissions-management', [RolePermissionController::class, 'index'])->name('roles-permissions.index');
    Route::get('/roles-permissions-management/roles', [RolePermissionController::class, 'getRoles'])->name('roles-permissions.roles');
});

require __DIR__.'/auth.php';
