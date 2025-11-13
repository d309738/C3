<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Hier definiëren we alle webroutes van de applicatie.
|
*/

// 🔹 Homepage
Route::get('/', function () {
    return view('pages.index');
})->name('home');

// 🔹 Routes voor ingelogde (en geverifieerde) gebruikers
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profiel
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Teams (TeamController, zonder edit/update)
    Route::resource('teams', TeamController::class)->except(['edit','update']);
});

// 🔹 Optioneel: extra alias routes (kun je houden of verwijderen)
Route::get('/index', function () {
    return view('pages.index');
})->name('index');

Route::get('/welcome', function () {
    return view('pages.index');
})->name('welcome');

// 🔹 Auth routes (login, register, etc.)
require __DIR__ . '/auth.php';
