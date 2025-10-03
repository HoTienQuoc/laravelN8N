<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('user-dashboard', function () {
    return Inertia::render('UserDashboard');
})->middleware(['auth'])->name('user-dashboard');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
