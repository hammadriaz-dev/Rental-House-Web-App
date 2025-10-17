<?php

use App\Http\Controllers\AdminPaymentLogController;
use App\Http\Controllers\AdminUserManagment;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LandlordPaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/',[FrontendController::class, 'index'])->name('welcome');
Route::get('property/{property}', [FrontendController::class, 'showPropertyDetails'])->name('property_deatils');
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

// ADMIN ROUTE
Route::middleware(['role:admin'])->prefix('admin')->group(function(){
    Route::get('dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    // Admin Messaging Monitoring (List of all threads)
    Route::get('messages', [MessageController::class, 'adminIndex'])->name('messages.index');
    // User manangement
    Route::resource('users', AdminUserManagment::class);
    // Property Moderation
    Route::get('property/moderation', [PropertyController::class, 'AdminIndex'])->name('moderation.index');
    Route::get('property/{property}/review', [PropertyController::class, 'Adminshow'])->name('moderation.show');
    Route::post('property/{property}/approve', [PropertyController::class, 'approve'])->name('moderation.approve');
    Route::post('property/{property}/reject', [PropertyController::class, 'reject'])->name('moderation.reject');
    // Payments Log
    Route::get('payments', [AdminPaymentLogController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [AdminPaymentLogController::class, 'show'])->name('payments.show');
});
// TENANT ROUTE
Route::middleware(['role:tenant'])->prefix('tenant')->group(function(){
    Route::get('dashboard', [DashboardController::class, 'tenant'])->name('tenant.dashboard');
    // UNIFIED MESSAGING APP for Tenant
    Route::get('messages/{otherUserId?}', [MessageController::class, 'messagesApp'])->name('tenant.messages.app');
    Route::post('messages', [MessageController::class, 'store'])->name('tenant.messages.store'); // Store action remains separate
    // Booking Routes
    Route::get('booking/{property}/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('booking/{property}', [BookingController::class, 'store'])->name('booking.store');
    Route::get('booking', [BookingController::class, 'bookings'])->name('my_booking');
});
// LANDLORD ROUTE
Route::middleware(['role:landlord'])->prefix('landlord')->group(function(){
    Route::get('dashboard', [DashboardController::class, 'landlord'])->name('landlord.dashboard');
    Route::resource('properties', PropertyController::class);
    // UNIFIED MESSAGING APP for Landlord
    Route::get('messages/{otherUserId?}', [MessageController::class, 'messagesApp'])->name('landlord.messages.app');
    Route::post('messages', [MessageController::class, 'store'])->name('landlord.messages.store');
    // Booking Request Route
    Route::get('booking/requests', [BookingController::class, 'bookingRequest'])->name('booking.requests');
    Route::post('booking/{booking}/approve', [BookingController::class, 'bookingApprove'])->name('bookings.approve');
    Route::post('booking/{booking}/reject', [BookingController::class, 'bookingReject'])->name('bookings.reject');
    // Payments route
    Route::get('payments', [LandlordPaymentController::class, 'index'])->name('landlord.payments.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
