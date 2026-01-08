<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MatchScoreController extends Controller
{
    public function update(Request $request, Matche $match)
    {
        $this->authorize('update', $match);

        $request->validate([
            'team1_score' => ['required','integer','min:0'],
            'team2_score' => ['required','integer','min:0'],
        ]);

        DB::transaction(function () use ($request, $match) {
            $match->team1_score = $request->input('team1_score');
            $match->team2_score = $request->input('team2_score');
            $match->save();

            // If this match is part of a competition and is a knockout match, try to advance winner
            if ($match->competition_id && in_array($match->round, ['Quarterfinal','Semifinal','Final'])) {
                $this->advanceWinner($match);
            }
        });

        return response()->json(['match' => $match->fresh()->load(['team1','team2'])]);
    }

    protected function advanceWinner(Matche $match)
    {
        // Determine winner
        if (!isset($match->team1_score) || !isset($match->team2_score)) {
            return;
        }

        if ($match->team1_score == $match->team2_score) {
            // Draws not allowed for knockout; do nothing
            return;
        }

        $winnerId = $match->team1_score > $match->team2_score ? $match->team1_id : $match->team2_id;

        // Find the next round match in same competition with a placeholder team (team1 or team2 equal to one of the placeholder ids)
        // We'll match by round: Quarterfinal -> Semifinal, Semifinal -> Final, Final -> mark competition winner
        $nextRoundMap = ['Quarterfinal' => 'Semifinal', 'Semifinal' => 'Final'];

        if (isset($nextRoundMap[$match->round])) {
            $nextRound = $nextRoundMap[$match->round];

            // Find first match in next round for same competition that still has a placeholder slot that is identical to first team's placeholder
            $candidate = Matche::where('competition_id', $match->competition_id)
                ->where('round', $nextRound)
                ->orderBy('time')
                ->get()
                ->first(function ($m) use ($match) {
                    // placeholder was set to first two teams (shuffled[0], shuffled[1]) so accept any with same placeholders
                    return is_null($m->team1_score) && is_null($m->team2_score);
                });

            if ($candidate) {
                // If team1 is null or is placeholder, fill the first empty slot
                if (is_null($candidate->team1_id)) {
                    $candidate->team1_id = $winnerId;
                    $candidate->save();
                } elseif (is_null($candidate->team2_id)) {
                    $candidate->team2_id = $winnerId;
                    $candidate->save();
                } else {
                    // both filled - no action
                }
            }
        } else {
            // Round was Final — mark competition winner (we'll store winner in competitions table name as suffix for simplicity)
            $competition = $match->competition;
            if ($competition) {
                $winnerTeam = \App\Models\Team::find($winnerId);
                if ($winnerTeam) {
                    $competition->winner = $winnerTeam->name;
                    $competition->save();
                }
            }
        }
    }
}
