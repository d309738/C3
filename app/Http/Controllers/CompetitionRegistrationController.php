<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Team;
use Illuminate\Http\Request;

class CompetitionRegistrationController extends Controller
{
    /**
     * Laat alle competities zien met checkboxen en teams
     */
    public function index()
    {
        $competitions = Competition::all(); // alle competities
        $teams = auth()->user()->teams;     // teams van ingelogde gebruiker

        return view('competitions.index', compact('competitions', 'teams'));
    }

    public function view()
{
    // Laad alle competities + teams
    $competitions = \App\Models\Competition::with('teams')->get();

    return view('competitions.view', compact('competitions'));
}

    /**
     * Verwerk het inschrijven van een team in één of meerdere competities
     */
    public function registerTeam(Request $request)
    {
        $request->validate([
            'team_id' => ['required', 'exists:teams,id'],
            'competition_ids' => ['required', 'array'],
            'competition_ids.*' => ['exists:competitions,id'],
        ]);

        $team = Team::findOrFail($request->team_id);

        // Voeg competities toe zonder eerdere inschrijvingen te verwijderen
        $team->competitions()->syncWithoutDetaching($request->competition_ids);

        return redirect()->back()->with('success', 'Team succesvol ingeschreven voor geselecteerde competities!');
    }
}
