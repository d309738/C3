<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CompetitionRegistrationController;
use App\Http\Controllers\MatcheController;
use App\Models\Matche;
use Illuminate\Support\Facades\Route;
use App\Models\Team;

// Homepage
Route::get('/', function () {
    $user = auth()->user();
    $teams = Team::all();
    $team = $user ? $user->teams()->first() : null;
    $players = $team ? $team->players : [];
    $top5teams = Team::orderByDesc('points')->take(5)->get();
    $team = $user?->teams()->first();
    $players = $team?->players ?? collect();
    $matches = Matche::all();
    return view('pages.index', compact('top5teams', 'user', 'players', 'teams', 'team', 'matches'));
})->name('home');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth middleware
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

    // Competities lijst + inschrijving
    Route::get('/competitions', [CompetitionRegistrationController::class, 'index'])
        ->name('competitions.index');

    Route::post('/competitions/register', [CompetitionRegistrationController::class, 'registerTeam'])
        ->name('competitions.registerTeam');

    Route::get('/competitions/view', [CompetitionRegistrationController::class, 'view'])
        ->name('competitions.view');
});

// Teams bekijken - publiek
Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');

// Matches
Route::post('/matche', [MatcheController::class, 'store'])
    ->name('matche.store')
    ->middleware('auth');

// Auth routes

Route::get('/matches/upcoming', function () {
    $matches = Matche::where('played', false)
        ->with(['team1:id,name', 'team2:id,name'])
        ->get()
        ->map(function ($match) {
            return [
                
                'match_id' => $match->id,
                'team1_id' => $match->team1_id,
                'team1_name' => $match->team1->name ?? 'Unknown',
                'team2_id' => $match->team2_id,
                'team2_name' => $match->team2->name ?? 'Unknown',
            ];
        });

    return response()->json($matches);
});
require __DIR__ . '/auth.php';

Route::get('/matchlist', function(){
    $matches = Matche::all();
    return response()->json($matches);
});
