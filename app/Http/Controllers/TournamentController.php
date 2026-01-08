<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $competitions = Competition::withCount(['teams'])->get();
        return view('pages.tournaments.index', compact('competitions'));
    }

    public function show(Competition $competition)
    {
        // Load matches grouped by round
        $matches = \App\Models\Matche::where('competition_id', $competition->id)
            ->with(['team1', 'team2'])
            ->orderBy('created_at')
            ->get()
            ->groupBy('round');

        return view('pages.tournaments.show', compact('competition', 'matches'));
    }
}
