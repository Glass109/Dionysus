<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/dashboard');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('dashboard/events/owned', [\App\Http\Controllers\EventoController::class, 'owned'])->middleware(['auth', 'verified'])->name('dashboard.events.owned');
Route::get('dashboard/events/explore', [\App\Http\Controllers\EventoController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard.events.subscribed');
Route::get('dashboard/events/subscribed', [\App\Http\Controllers\EventoController::class, 'subscribed'])->middleware(['auth', 'verified'])->name('dashboard.events.subscribed');
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
