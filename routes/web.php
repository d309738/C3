<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CompetitionRegistrationController;
use Illuminate\Support\Facades\Route;
use App\Models\Team;

// Homepage
Route::get('/', function () {
    $user = auth()->user();
    $team = auth()->user()->teams()->first();
    $players = $team->players;
    $top5teams = Team::orderByDesc('points', 'desc')->take(5)->get();
    return view('pages.index', compact('top5teams', 'user', 'players'));
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Ingelogde en geverifieerde routes
Route::middleware(['auth','verified'])->group(function () {

    // Profiel
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Teams CRUD
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');

    // Competitie overzicht + checkbox inschrijving
    Route::get('/competitions', [CompetitionRegistrationController::class, 'index'])
        ->name('competitions.index');
    Route::post('/competitions/register', [CompetitionRegistrationController::class, 'registerTeam'])
        ->name('competitions.registerTeam');
});

// Teams bekijken - voor iedereen
Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');

// Auth routes
require __DIR__ . '/auth.php';
