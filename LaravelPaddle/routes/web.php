<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserAdmin;
use App\Http\Controllers\PaddleController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

//Admin Routes

Route::get('dashboard', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth',EnsureUserAdmin::class])->name('dashboard');

Route::get('admin/create-blog', function () {
    return Inertia::render('Admin/CreateBlog');
})->middleware(['auth', EnsureUserAdmin::class])->name('admin.create-blog');

Route::get('admin/users', function () {
    return Inertia::render('Admin/Users');
})->middleware(['auth', EnsureUserAdmin::class])->name('admin.users');

Route::get('admin/blogs', function () {
    return Inertia::render('Admin/Blogs');
})->middleware(['auth', EnsureUserAdmin::class])->name('admin.blogs');

//User Routes
Route::get('pricing', function () {
    return Inertia::render('User/Pricing');
})->middleware(['auth'])->name('pricing');

Route::get('refund', function () {
    return Inertia::render('User/Refund');
})->middleware(['auth'])->name('refund');

Route::get('user-dashboard', function () {
    return Inertia::render('User/Dashboard');
})->middleware(['auth'])->name('user-dashboard');

//Paddle webhook
// Route::post('webhook/paddle', function (Request $request) {
//     // Handle Paddle webhook logic here

//     // Example: Log the webhook data
//     Log::info('Paddle Webhook Received:', $request->all());

//     // Respond with a 200 OK status
//     return response()->json(['status' => 'success'], 200);
// })->name('webhook.paddle');
Route::post('webhook/paddle', [PaddleController::class, 'handlePaddleWebhook'])->name('webhook.paddle');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
