<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/dashboard');
})->name('home');


Route::get('/dashboard', function () {
    return redirect('/events/explore');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/events/owned', [\App\Http\Controllers\EventoController::class, 'owned'])->middleware(['auth', 'verified'])->name('events.owned');
Route::get('/events/explore', [\App\Http\Controllers\EventoController::class, 'index'])->middleware(['auth', 'verified'])->name('events.subscribed');
Route::get('/events/subscribed', [\App\Http\Controllers\EventoController::class, 'subscribed'])->middleware(['auth', 'verified'])->name('events.subscribed');
Route::get('/events/{id}', [\App\Http\Controllers\EventoController::class, 'show'])->middleware(['auth', 'verified'])->name('events.show');

// Handle subscription
// Handle subscription/unsubscription
Route::post('/events/subscribe', [\App\Http\Controllers\EventoController::class, 'handleSubscribe'])
    ->middleware(['auth', 'verified'])->name('events.handleSubscribe');
Route::post('/events/unsubscribe', [\App\Http\Controllers\EventoController::class, 'handleUnsubscribe'])
    ->middleware(['auth', 'verified'])->name('events.handleUnsubscribe');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
