<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserAdmin;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth',EnsureUserAdmin::class])->name('dashboard');

Route::get('user-dashboard', function () {
    return Inertia::render('UserDashboard');
})->middleware(['auth'])->name('user-dashboard');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
