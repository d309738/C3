<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matche;
use Illuminate\Http\Request;

class MatchApiController extends Controller
{
    /**
     * Geplande wedstrijden (nog niet gespeeld)
     */
    public function upcoming()
    {
        $matches = Matche::with(['team1', 'team2'])
            ->whereNull('score_team1')
            ->whereNull('score_team2')
            ->get()
            ->map(function ($match) {
                return [
                    'match_id' => $match->id,
                    'team1_id' => $match->team1_id,
                    'team1_name' => $match->team1->name ?? 'Onbekend',
                    'team2_id' => $match->team2_id,
                    'team2_name' => $match->team2->name ?? 'Onbekend',
                ];
            });

        return response()->json($matches);
    }

    /**
     * Gespeelde wedstrijden (met score)
     */
    public function played()
    {
        $matches = Matche::with(['team1', 'team2'])
            ->whereNotNull('score_team1')
            ->whereNotNull('score_team2')
            ->get()
            ->map(function ($match) {
                return [
                    'match_id' => $match->id,
                    'team1_id' => $match->team1_id,
                    'team1_name' => $match->team1->name ?? 'Onbekend',
                    'team2_id' => $match->team2_id,
                    'team2_name' => $match->team2->name ?? 'Onbekend',
                    'team1_score' => $match->score_team1,
                    'team2_score' => $match->score_team2,
                ];
            });

        return response()->json($matches);
    }
}
