<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Player;

class TeamController extends Controller
{
    // Alleen ingelogde gebruikers
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Toon alle teams van de ingelogde gebruiker
    public function index()
    {
        $teams = Team::where('user_id', auth()->id())->with('players')->get();
        return view('pages.teams.index', compact('teams'));
    }

    // Formulier om een nieuw team aan te maken
    public function create()
    {
        return view('pages.teams.create');
    }

    // Opslaan van een nieuw team + spelers
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'coach_name' => 'required|string|max:255',
            'players' => 'required|array|min:1',
            'players.*' => 'required|string|max:255',
        ]);

        // Team aanmaken
        $team = Team::create([
            'name' => $request->name,
            'city' => $request->city,
            'coach_name' => $request->coach_name,
            'user_id' => auth()->id(),
        ]);

        // Spelers toevoegen
        foreach ($request->players as $playerName) {
            $team->players()->create(['name' => $playerName]);
        }

        return redirect()->route('teams.index')->with('success', 'Team succesvol aangemaakt!');
    }

    // Bekijk een team
    public function show($id)
    {
        $team = Team::where('user_id', auth()->id())->with('players')->findOrFail($id);
        return view('pages.teams.show', compact('team'));
    }

    // Verwijder een team
    public function destroy($id)
    {
        $team = Team::where('user_id', auth()->id())->findOrFail($id);
        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team verwijderd!');
    }
}
