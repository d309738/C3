<?php

namespace App\Http\Controllers;

use App\Models\Matche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatcheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        return view('matches.show', compact('match'));
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
