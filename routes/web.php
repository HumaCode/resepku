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
    Route::post('/roles-permissions-management/roles', [RolePermissionController::class, 'store'])->name('roles-permissions.store');
    Route::put('/roles-permissions-management/roles/{role}', [RolePermissionController::class, 'update'])->name('roles-permissions.update');
    Route::patch('/roles-permissions-management/roles/{role}/toggle-active', [RolePermissionController::class, 'toggleActive'])->name('roles-permissions.toggle-active');
    Route::delete('/roles-permissions-management/roles/{role}', [RolePermissionController::class, 'destroy'])->name('roles-permissions.destroy');
    Route::post('/roles-permissions-management/permissions', [RolePermissionController::class, 'syncPermissions'])->name('roles-permissions.sync');

    // Permissions Routes
    Route::middleware('can:menu permissions')->group(function () {
        Route::get('/permissions', [\App\Http\Controllers\Pengguna\PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/list', [\App\Http\Controllers\Pengguna\PermissionController::class, 'list'])->name('permissions.list');
        Route::post('/permissions', [\App\Http\Controllers\Pengguna\PermissionController::class, 'store'])->name('permissions.store');
        Route::put('/permissions/{permission}', [\App\Http\Controllers\Pengguna\PermissionController::class, 'update'])->name('permissions.update');
        Route::patch('/permissions/{permission}/toggle-active', [\App\Http\Controllers\Pengguna\PermissionController::class, 'toggleActive'])->name('permissions.toggle-active');
        Route::delete('/permissions/{permission}', [\App\Http\Controllers\Pengguna\PermissionController::class, 'destroy'])->name('permissions.destroy');
    });

    // Categories Routes
    Route::get('/categories', [\App\Http\Controllers\MasterData\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/list', [\App\Http\Controllers\MasterData\CategoryController::class, 'getCategories'])->name('categories.list');
    Route::post('/categories', [\App\Http\Controllers\MasterData\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [\App\Http\Controllers\MasterData\CategoryController::class, 'update'])->name('categories.update');
    Route::patch('/categories/{category}/toggle-active', [\App\Http\Controllers\MasterData\CategoryController::class, 'toggleActive'])->name('categories.toggle-active');
    Route::delete('/categories/{category}', [\App\Http\Controllers\MasterData\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Tags Routes
    Route::get('/tags', [\App\Http\Controllers\MasterData\TagController::class, 'index'])->name('tags.index');
    Route::get('/tags/list', [\App\Http\Controllers\MasterData\TagController::class, 'getTags'])->name('tags.list');
    Route::post('/tags', [\App\Http\Controllers\MasterData\TagController::class, 'store'])->name('tags.store');
    Route::put('/tags/{tag}', [\App\Http\Controllers\MasterData\TagController::class, 'update'])->name('tags.update');
    Route::patch('/tags/{tag}/toggle-active', [\App\Http\Controllers\MasterData\TagController::class, 'toggleActive'])->name('tags.toggle-active');
    Route::delete('/tags/{tag}', [\App\Http\Controllers\MasterData\TagController::class, 'destroy'])->name('tags.destroy');

    // Ingredients Routes
    Route::middleware('can:menu ingredients')->group(function () {
        Route::get('/ingredients', [\App\Http\Controllers\MasterData\IngredientController::class, 'index'])->name('ingredients.index');
        Route::get('/ingredients/list', [\App\Http\Controllers\MasterData\IngredientController::class, 'list'])->name('ingredients.list');
        Route::post('/ingredients', [\App\Http\Controllers\MasterData\IngredientController::class, 'store'])->name('ingredients.store');
        Route::put('/ingredients/{ingredient}', [\App\Http\Controllers\MasterData\IngredientController::class, 'update'])->name('ingredients.update');
        Route::patch('/ingredients/{ingredient}/toggle-active', [\App\Http\Controllers\MasterData\IngredientController::class, 'toggleActive'])->name('ingredients.toggle-active');
        Route::delete('/ingredients/{ingredient}', [\App\Http\Controllers\MasterData\IngredientController::class, 'destroy'])->name('ingredients.destroy');
    });
});

require __DIR__.'/auth.php';
