<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 🔹 Homepage verwijst nu naar resources/views/pages/index.blade.php
Route::get('/', function () {
    return view('pages.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
})->middleware('verified');

<<<<<<< HEAD
Route::get('index', function () {
    return view('pages.index');
})->name('index');

require __DIR__.'/auth.php';
=======
// 🔹 Optioneel: extra route /welcome (ook naar index.blade.php)
Route::get('/welcome', function () {
    return view('pages.index');
})->name('welcome');

require __DIR__ . '/auth.php';
>>>>>>> a0f196f952657fbec6933243cf4ead11672f11e2
