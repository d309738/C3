<?php

namespace App\Http\Controllers;

use App\Models\Matche;
use Illuminate\Http\Request;
use App\Models\Player;

class MatcheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $teams = Team::all();
        $team = $user ? $user->teams()->first() : null;
        $players = $team ? $team->players : [];
        $top5teams = Team::orderByDesc('points')->take(5)->get();
        $team = $user?->teams()->first();
        $players = $team?->players ?? collect();
        $matches = Matche::all();
        return view('pages.index', compact('top5teams', 'user', 'players', 'teams', 'team', 'matches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $match = Matche::create($request->all());

        // After creating a match, redirect to the result form so the match id is visible
        return redirect()->route('matches.result.form', ['match' => $match->id]);
    }

    /**
     * Show a simple form to submit match result.
     */
    public function showResult(Matche $match)
    {
        // optional: authorize
        // $this->authorize('update', $match);
        return view('matches.result', compact('match'));
    }

    /**
     * Display the specified match and its result.
     */
    public function show(Matche $match)
    {
        $players = Player::all();
        $scores1 = $match->team1_score ?? 0;
        $scores2 = $match->team2_score ?? 0;
        $scores = $scores1 + $scores2;
        return view('matches.show', compact('match', 'players', 'scores'));
    }

    /**
     * List all matches with results.
     */
    public function results()
    {
        $matches = Matche::whereNotNull('team1_score')
            ->whereNotNull('team2_score')
            ->with(['team1', 'team2'])
            ->orderByDesc('updated_at')
            ->get();

        return view('matches.results', compact('matches'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
