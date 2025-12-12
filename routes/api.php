<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Matche;
use App\Models\Team;
use App\Http\Controllers\Api\MatchApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Deze routes zijn openbaar toegankelijk (geen login nodig).
| Ze geven JSON terug voor externe apps zoals WinUI / C#.
|--------------------------------------------------------------------------
*/

// ✔ Upcoming wedstrijden (nog niet gespeeld)
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

// ✔ Gespeelde wedstrijden (finished)
Route::get('/matches/finished', function () {
    $matches = Matche::where('played', true)
        ->with(['team1:id,name', 'team2:id,name'])
        ->get()
        ->map(function ($match) {
            return [
                'match_id' => $match->id,
                'team1_id' => $match->team1_id,
                'team1_name' => $match->team1->name ?? 'Unknown',
                'team1_score' => $match->team1_score,
                'team2_id' => $match->team2_id,
                'team2_name' => $match->team2->name ?? 'Unknown',
                'team2_score' => $match->team2_score,
            ];
        });

    return response()->json($matches);
});
