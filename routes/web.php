<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('landlord')) {
        return redirect()->route('landlord.dashboard');
    }

    if ($user->hasRole('tenant')) {
        return redirect()->route('tenant.dashboard');
    }

    return redirect('/');
})->middleware('auth')->name('dashboard');


Route::middleware(['role:admin'])->prefix('admin')->group(function(){
    Route::get('dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
});
Route::middleware(['role:tenant'])->prefix('tenant')->group(function(){
    Route::get('dashboard', [DashboardController::class, 'tenant'])->name('tenant.dashboard');
});
Route::middleware(['role:landlord'])->prefix('landlord')->group(function(){
    Route::get('dashboard', [DashboardController::class, 'landlord'])->name('landlord.dashboard');
    Route::resource('property', PropertyController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
