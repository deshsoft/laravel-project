<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BookingEventsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\IncomeReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/login'); // Redirect homepage to login
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer Management Routes
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/view', [CustomerController::class, 'view'])->name('view');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/store', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
    });
    // Booking Events Routes
    Route::prefix('booking-events')->name('booking-events.')->group(function () {
        Route::get('/', [BookingEventsController::class, 'index'])->name('index');
        Route::get('/calendar', [BookingEventsController::class, 'calendar'])->name('calendar');
        Route::get('/calendar-events', [BookingEventsController::class, 'fetchEvents'])->name('events');
        Route::post('/check-aggregable-availability', [BookingEventsController::class, 'checkAggregableAvailability'])->name('checkAggregableAvailability');
        Route::get('/create', [BookingEventsController::class, 'create'])->name('create');
        Route::post('/store', [BookingEventsController::class, 'store'])->name('store');
        Route::get('/{booking_event}/edit', [BookingEventsController::class, 'edit'])->name('edit');
        Route::get('/{booking_event}/view', [BookingEventsController::class, 'view'])->name('view');
        Route::patch('/{booking_event}/toggle-status', [BookingEventsController::class, 'toggleStatus'])->name('toggle-status');
        Route::put('/{booking_event}', [BookingEventsController::class, 'update'])->name('update');
        Route::delete('/{booking_event}', [BookingEventsController::class, 'destroy'])->name('destroy');
    });
    // Route::get('/calendar-events', [CalendarController::class, 'fetchEvents'])->name('calendar.events');
    // Calendar Routes
    Route::prefix('booking-calendar')->name('booking-calendar.')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('index');
        Route::get('/view', [CalendarController::class, 'view'])->name('view');
        Route::get('/create', [CalendarController::class, 'create'])->name('create');
        Route::post('/store', [CalendarController::class, 'store'])->name('store');
        Route::get('/{booking_event}/edit', [CalendarController::class, 'edit'])->name('edit');
        Route::put('/{booking_event}', [CalendarController::class, 'update'])->name('update');
        Route::delete('/{booking_event}', [CalendarController::class, 'destroy'])->name('destroy');
    });

    Route::get('/income-report', [IncomeReportController::class, 'index'])->name('income.report');


});

Route::middleware(['auth', 'admin'])->group(function () {
    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/view', [UserController::class, 'view'])->name('view');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Asset Management Routes
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::get('/view', [AssetController::class, 'view'])->name('view');
        Route::get('/create', [AssetController::class, 'create'])->name('create');
        Route::post('/store', [AssetController::class, 'store'])->name('store');
        Route::get('/{asset}/edit', [AssetController::class, 'edit'])->name('edit');
        Route::put('/{asset}', [AssetController::class, 'update'])->name('update');
        Route::delete('/{asset}', [AssetController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
