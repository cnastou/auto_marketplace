<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransportRequestController;
use App\Http\Controllers\BidController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');


/*
|--------------------------------------------------------------------------
| Transport Requests
|--------------------------------------------------------------------------
*/

Route::get('/transport-requests', [TransportRequestController::class, 'index'])
    ->name('transport-requests.index');

Route::get('/transport-requests/{transportRequest}', [TransportRequestController::class, 'show'])
    ->name('transport-requests.show');


/*
|--------------------------------------------------------------------------
| Bids
|--------------------------------------------------------------------------
*/

Route::post('/bids', [BidController::class, 'store'])
    ->middleware('auth')
    ->name('bids.store');

Route::patch('/bids/{bid}/accept', [BidController::class, 'accept'])
    ->middleware('auth')
    ->name('bids.accept');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';