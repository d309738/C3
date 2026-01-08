<?php

namespace App\Http\Controllers;

use App\Models\Matche;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    public function create()
    {
        $teams = Team::orderBy('name')->get();
        return view('pages.schedules.create', compact('teams'));
    }

    public function generate(Request $request)
    {
        // Parse inputs
        $teamIds = $request->input('team_ids', null); // array or null
        $format = $request->input('format', 'round-robin'); // 'round-robin' or 'knockout'
        $start = $request->input('start_time', null); // optional datetime string

        // Load teams
        if (is_array($teamIds)) {
            if (count($teamIds) !== 8) {
                return response()->json(['message' => 'Wanneer u teams selecteert, moet u precies 8 team-IDs doorgeven.'], 422);
            }

            $teams = Team::whereIn('id', $teamIds)->get();
            if ($teams->count() !== 8) {
                return response()->json(['message' => 'Een of meer opgegeven teams bestaan niet.'], 422);
            }
        } else {
            $teams = Team::inRandomOrder()->limit(8)->get();
            if ($teams->count() < 8) {
                return response()->json(['message' => 'Er moeten minimaal 8 teams zijn om een schema te genereren.'], 422);
            }
        }

        // Find a referee (use authenticated user or first user as fallback)
        $referee = $request->user() ?? User::first();
        if (!$referee) {
            return response()->json(['message' => 'Geen gebruiker beschikbaar om als scheidsrechter toe te wijzen. Maak eerst een gebruiker aan.'], 422);
        }

        $preview = $request->boolean('preview', false);
        $created = [];
        $previewMatches = [];

        // Create competition if name supplied (and not a preview)
        $competitionId = null;
        if (!$preview && $request->filled('tournament_name')) {
            $competition = \App\Models\Competition::create(['name' => $request->input('tournament_name')]);
            $competitionId = $competition->id;
        }

        DB::transaction(function () use ($teams, $referee, $format, $start, $preview, &$created, &$previewMatches, $competitionId) {
            $n = $teams->count();
            $baseTime = $start ? Carbon::parse($start) : Carbon::now();
            $minutes = 0;

            if ($format === 'knockout') {
                // Shuffle teams
                $shuffled = $teams->shuffle()->values();

                // Quarterfinals (4 matches)
                for ($i = 0; $i < 8; $i += 2) {
                    $data = [
                        'team1_id' => $shuffled[$i]->id,
                        'team2_id' => $shuffled[$i+1]->id,
                        'team1_score' => null,
                        'team2_score' => null,
                        'field' => 'TBD',
                        'referee_id' => $referee->id,
                        'time' => $baseTime->copy()->addMinutes($minutes)->toDateTimeString(),
                        'round' => 'Quarterfinal',
                    ];

                    if ($preview) {
                        $t1 = $teams->firstWhere('id', $data['team1_id']) ?? Team::find($data['team1_id']);
                        $t2 = $teams->firstWhere('id', $data['team2_id']) ?? Team::find($data['team2_id']);
                        $previewMatches[] = array_merge($data, [
                            'team1' => $t1 ? ['id' => $t1->id, 'name' => $t1->name] : null,
                            'team2' => $t2 ? ['id' => $t2->id, 'name' => $t2->name] : null,
                        ]);
                    } else {
                        $m = Matche::create($data);
                        $created[] = $m->id;
                    }

                    $minutes += 60;
                }

                // Semifinal placeholders (2 matches) - use first teams as placeholders so DB constraints are satisfied
                for ($s = 0; $s < 2; $s++) {
                    $data = [
                        'team1_id' => $shuffled[0]->id,
                        'team2_id' => $shuffled[1]->id,
                        'team1_score' => null,
                        'team2_score' => null,
                        'field' => 'TBD',
                        'referee_id' => $referee->id,
                        'time' => $baseTime->copy()->addMinutes($minutes)->toDateTimeString(),
                        'round' => 'Semifinal',
                    ];

                    if ($preview) {
                        $t1 = $teams->firstWhere('id', $data['team1_id']) ?? Team::find($data['team1_id']);
                        $t2 = $teams->firstWhere('id', $data['team2_id']) ?? Team::find($data['team2_id']);
                        $previewMatches[] = array_merge($data, [
                            'team1' => $t1 ? ['id' => $t1->id, 'name' => $t1->name] : null,
                            'team2' => $t2 ? ['id' => $t2->id, 'name' => $t2->name] : null,
                        ]);
                    } else {
                        $m = Matche::create($data);
                        $created[] = $m->id;
                    }

                    $minutes += 60;
                }

                // Final placeholder
                $data = [
                    'team1_id' => $shuffled[0]->id,
                    'team2_id' => $shuffled[1]->id,
                    'team1_score' => null,
                    'team2_score' => null,
                    'field' => 'TBD',
                    'referee_id' => $referee->id,
                    'time' => $baseTime->copy()->addMinutes($minutes)->toDateTimeString(),
                    'round' => 'Final',
                ];

                if ($preview) {
                    $t1 = $teams->firstWhere('id', $data['team1_id']) ?? Team::find($data['team1_id']);
                    $t2 = $teams->firstWhere('id', $data['team2_id']) ?? Team::find($data['team2_id']);
                    $previewMatches[] = array_merge($data, [
                        'team1' => $t1 ? ['id' => $t1->id, 'name' => $t1->name] : null,
                        'team2' => $t2 ? ['id' => $t2->id, 'name' => $t2->name] : null,
                    ]);
                } else {
                    $m = Matche::create($data + (isset($competitionId) ? ['competition_id' => $competitionId] : []));
                    $created[] = $m->id;
                }

            } else {
                // Default round-robin: every pair plays once
                for ($i = 0; $i < $n - 1; $i++) {
                    for ($j = $i + 1; $j < $n; $j++) {
                        $data = [
                            'team1_id' => $teams[$i]->id,
                            'team2_id' => $teams[$j]->id,
                            'team1_score' => null,
                            'team2_score' => null,
                            'field' => 'TBD',
                            'referee_id' => $referee->id,
                            'time' => $baseTime->copy()->addMinutes($minutes)->toDateTimeString(),
                            'round' => 'Round-Robin',
                        ];

                        if ($preview) {
                            $previewMatches[] = $data;
                        } else {
                            $m = Matche::create($data);
                            $created[] = $m->id;
                        }

                        $minutes += 30; // space matches by 30 minutes
                    }
                }
            }
        });

        if ($preview) {
            // return preview matches with team relationships populated
            $matches = collect($previewMatches)->map(function ($m) {
                return $m;
            })->all();
            return response()->json(['matches' => $matches]);
        }

        $matches = Matche::with(['team1', 'team2'])->whereIn('id', $created)->get();

        return response()->json(['matches' => $matches]);
    }
}
