<?php

namespace App\Http\Controllers;

use App\Models\Competition;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::with('teams')->get();

        return view('competitions.list', compact('competitions'));
    }

    public function show(Competition $competition)
    {
        $competition->load('teams');

        return view('competitions.show', compact('competition'));
    }
}
