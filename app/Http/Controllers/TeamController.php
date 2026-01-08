<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Player;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    // Teams overzicht
    public function index()
    {
        $teams = Team::with('players')->get();
        return view('pages.teams.index', compact('teams'));
    }

    // Toon form voor nieuw team
    public function create()
    {
        $teams = Team::orderBy('name')->get();
        return view('pages.teams.create', compact('teams'));
    }

    // Opslaan nieuw team
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'coach_name' => 'required|string|max:255',
            'players' => 'required|array|min:1',
            'players.*' => 'required|string|max:255'
        ]);

        $team = Team::create([
            'name' => $request->name,
            'city' => $request->city,
            'coach_name' => $request->coach_name,
            'user_id' => auth()->id(),
        ]);

        foreach ($request->players as $playerName) {
            $team->players()->create(['name' => $playerName]);
        }

        return redirect()->route('teams.index')->with('success', 'Team succesvol aangemaakt!');
    }

    // Toon team
    public function show($id)
    {
        $team = Team::with('players')->findOrFail($id);
        return view('pages.teams.show', compact('team'));
    }

    // Edit team
    public function edit($id)
    {
        $team = Team::with('players')->findOrFail($id);
        return view('pages.teams.edit', compact('team'));
    }

    // Update team
    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'coach_name' => 'required|string|max:255',
            'players' => 'required|array|min:1',
            'players.*' => 'required|string|max:255'
        ]);

        $team->update([
            'name' => $request->name,
            'city' => $request->city,
            'coach_name' => $request->coach_name,
        ]);

        $team->players()->delete();
        foreach ($request->players as $playerName) {
            $team->players()->create(['name' => $playerName]);
        }

        return redirect()->route('teams.index')->with('success', 'Team succesvol bijgewerkt!');
    }

    // Verwijder team
    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team verwijderd!');
    }
}
