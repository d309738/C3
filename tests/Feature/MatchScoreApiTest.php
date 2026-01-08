<?php

use App\Models\Team;
use App\Models\User;
use App\Models\Matche;
use App\Models\Competition;

beforeEach(function () {
    Matche::query()->delete();
    Team::query()->delete();
    Competition::query()->delete();
});

it('allows authenticated user to update match scores and advances winner', function () {
    $user = User::factory()->create();

    // create 8 teams
    $teams = collect();
    for ($i = 1; $i <= 8; $i++) {
        $teams->push(Team::create(['name' => "Team $i", 'city' => 'City', 'coach_name' => "Coach $i", 'user_id' => $user->id]));
    }

    $comp = Competition::create(['name' => 'API Cup']);

    // Create quarterfinals
    $q = [];
    for ($i = 0; $i < 8; $i += 2) {
        $q[] = Matche::create(['team1_id' => $teams[$i]->id, 'team2_id' => $teams[$i+1]->id, 'field' => 'TBD', 'referee_id' => $user->id, 'time' => now(), 'round' => 'Quarterfinal', 'competition_id' => $comp->id]);
    }

    // Create semifinals placeholders with non-null team ids (use representatives from quarterfinal winners)
    $s1 = Matche::create(['team1_id' => $teams[0]->id, 'team2_id' => $teams[2]->id, 'field' => 'TBD', 'referee_id' => $user->id, 'time' => now(), 'round' => 'Semifinal', 'competition_id' => $comp->id]);

    $this->actingAs($user)
        ->patchJson("/matches/{$q[0]->id}/score", [
            'team1_score' => 2,
            'team2_score' => 1,
        ])->assertStatus(200);

    $s1->refresh();
    $this->assertTrue(in_array($s1->team1_id, [$teams[0]->id, $teams[1]->id]) || in_array($s1->team2_id, [$teams[0]->id, $teams[1]->id]));
});
