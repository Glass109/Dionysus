<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\AdminMiddleware;
Route::get('/', function () {
    return redirect('/dashboard');
})->name('home');

Route::get('/dashboard', function () {
    return redirect('/events/explore');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/profile', function () {
    return Inertia::render('Profile/Index');
})->middleware(['auth', 'verified'])->name('profile.show');

Route::get('/events', function () {
    return redirect('/events/explore');
})->middleware(['auth', 'verified'])->name('events.index');
Route::get('/events/owned', [\App\Http\Controllers\EventoController::class, 'owned'])->middleware(['auth', 'verified'])->name('events.owned');
Route::get('/events/explore', [\App\Http\Controllers\EventoController::class, 'index'])->middleware(['auth', 'verified'])->name('events.subscribed');
Route::get('/events/subscribed', [\App\Http\Controllers\EventoController::class, 'subscribed'])->middleware(['auth', 'verified'])->name('events.subscribed');
Route::get('/events/create', [\App\Http\Controllers\EventoController::class, 'create'])->middleware(['auth', 'verified'])->name('events.create');
Route::get('/events/{id}', [\App\Http\Controllers\EventoController::class, 'show'])->middleware(['auth', 'verified'])->name('events.show');

// Handle subscription/unsubscription
Route::post('/events/subscribe', [\App\Http\Controllers\EventoController::class, 'handleSubscribe'])
    ->middleware(['auth', 'verified'])->name('events.handleSubscribe');
Route::post('/events/unsubscribe', [\App\Http\Controllers\EventoController::class, 'handleUnsubscribe'])
    ->middleware(['auth', 'verified'])->name('events.handleUnsubscribe');
Route::post('/events/cancel', [\App\Http\Controllers\EventoController::class, 'handleCancel'])
    ->middleware(['auth', 'verified'])->name('events.handleCancel');
Route::post('/events/store', [\App\Http\Controllers\EventoController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('events.store');

Route::get('/admin', function () {
    return Inertia::render('Admin/Index');
})->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin.index');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
