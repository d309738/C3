<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matche;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class MatchApiController extends Controller
{
    /**
     * Upcoming matches: scheduled but not played yet OR in future.
     * Returns: match id, team_a_id, team_b_id, team_a_name, team_b_name, datetime, location
     */
    public function upcoming()
    {
        // Upcoming: no scores set yet
        $matches = Matche::where(function($q){
                $q->whereNull('team1_score')->whereNull('team2_score');
            })
            ->with(['teamA','teamB'])
            ->get()
            ->map(function($m){
                return [
                    'id' => $m->id,
                    'team_a_id' => $m->team_a_id,
                    'team_b_id' => $m->team_b_id,
                    'team_a_name' => $m->teamA ? $m->teamA->name : null,
                    'team_b_name' => $m->teamB ? $m->teamB->name : null,
                    'datetime' => $m->scheduled_at ?? $m->datetime ?? null,
                    'location' => $m->location ?? null,
                ];
            });

        return response()->json($matches, 200);
    }

    /**
     * Played matches: have scores
     * Returns: match id, team ids, team names, score_a, score_b, datetime, location
     */
    public function played()
    {
        $matches = Matche::whereNotNull('team1_score')
            ->whereNotNull('team2_score')
            ->with(['teamA','teamB'])
            ->get()
            ->map(function($m){
                return [
                    'id' => $m->id,
                    'team_a_id' => $m->team_a_id,
                    'team_b_id' => $m->team_b_id,
                    'team_a_name' => $m->teamA ? $m->teamA->name : null,
                    'team_b_name' => $m->teamB ? $m->teamB->name : null,
                    'score_a' => $m->score_a,
                    'score_b' => $m->score_b,
                    'datetime' => $m->scheduled_at ?? $m->datetime ?? null,
                    'location' => $m->location ?? null,
                ];
            });

        return response()->json($matches, 200);
    }

    /**
     * Store result for a match and update team points.
     * Expected JSON payload:
     * {
     *   "score_a": 2,
     *   "score_b": 1
     * }
     */
    public function storeResult(Request $request, Matche $match)
    {
        $data = $request->validate([
            'score_a' => ['required', 'integer', 'min:0'],
            'score_b' => ['required', 'integer', 'min:0'],
        ]);

        // Wrap in transaction to avoid race conditions
        DB::transaction(function() use ($match, $data) {
            // Load teams
            $teamA = Team::find($match->team_a_id);
            $teamB = Team::find($match->team_b_id);

            if (!$teamA || !$teamB) {
                abort(422, 'Match teams not found');
            }

            // Calculate previous points from existing score (if any) to reverse
            $prevPointsA = 0;
            $prevPointsB = 0;
            if (!is_null($match->score_a) && !is_null($match->score_b)) {
                if ($match->score_a > $match->score_b) {
                    $prevPointsA = 3; $prevPointsB = 0;
                } elseif ($match->score_a < $match->score_b) {
                    $prevPointsA = 0; $prevPointsB = 3;
                } else {
                    $prevPointsA = 1; $prevPointsB = 1;
                }
            }

            // Subtract previous points (so we can update safely when result changes)
            if ($prevPointsA > 0 || $prevPointsB > 0) {
                $teamA->points = max(0, ($teamA->points ?? 0) - $prevPointsA);
                $teamB->points = max(0, ($teamB->points ?? 0) - $prevPointsB);
                $teamA->save();
                $teamB->save();
            }

            // Save new scores
            $match->score_a = (int)$data['score_a'];
            $match->score_b = (int)$data['score_b'];
            $match->save();

            // Assign new points
            $newPointsA = 0;
            $newPointsB = 0;
            if ($match->score_a > $match->score_b) {
                $newPointsA = 3;
            } elseif ($match->score_a < $match->score_b) {
                $newPointsB = 3;
            } else {
                $newPointsA = 1;
                $newPointsB = 1;
            }

            $teamA->points = ($teamA->points ?? 0) + $newPointsA;
            $teamB->points = ($teamB->points ?? 0) + $newPointsB;
            $teamA->save();
            $teamB->save();
        });

        // If the request expects JSON (API client), return JSON.
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Result saved and team points updated.'], 200);
        }

        // For web form submissions, redirect to the match view page.
        return redirect()->route('matches.view', ['match' => $match->id]);
    }
}
