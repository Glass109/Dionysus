<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('dashboard/events/owned', function () {
    return Inertia::render('Events/Owned');
})->middleware(['auth', 'verified'])->name('dashboard.events.owned');

Route::get('dashboard/events', function () {
    return Inertia::render('Events/SubscribedEvents');
})->middleware(['auth', 'verified'])->name('dashboard.events.subscribed');

Route::get('dashboard/events/explore', function () {
    return Inertia::render('Events/Explore');
})->middleware(['auth', 'verified'])->name('dashboard.events.explore');


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
