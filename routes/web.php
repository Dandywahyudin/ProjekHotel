<?php

use App\Models\Hotels;
use App\Models\Country;
use App\Models\HotelBooking;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HotelsController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HotelRoomController;
use App\Http\Controllers\HotelBookingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function() {
        Route::middleware('can:manage cities')->group(function() {
            Route::resource('cities', CityController::class);
        });

        Route::middleware('can:manage countries')->group(function() {
            Route::resource('country', CountryController::class);
        });
        
        Route::middleware('can:manage hotels')->group(function() {
            Route::resource('hotels', HotelsController::class);
        });
        
        Route::middleware('can:manage hotels')->group(function() {
            Route::get('/add/room/{hotel:slug}', [HotelRoomController::class, 'create'])->name('hotel_rooms.create');
            Route::post('/add/room/{hotel:slug}/store', [HotelRoomController::class, 'store'])->name('hotel_rooms.store');
            Route::get('/hotel/{hotel:slug}/room/{hotel_room}/', [HotelRoomController::class, 'edit'])->name('hotel_rooms.edit');
            Route::put('/room/{hotel_room}/update', [HotelRoomController::class, 'update'])->name('hotel_rooms.update');
            Route::delete('/hotel/{hotel:slug}/delete/{hotel_room}', [HotelRoomController::class, 'destroy'])->name('hotel_rooms.destroy');
        });

        Route::middleware('can:manage hotel booking')->group(function () {
            Route::resource('hotel_bookings', HotelBookingController::class);
        });
    });
});

require __DIR__.'/auth.php';


