<?php

use App\Models\Team;
use App\Models\Competition;
use App\Models\Matche;
use App\Models\User;

beforeEach(function () {
    Matche::query()->delete();
    Team::query()->delete();
    Competition::query()->delete();
    User::query()->delete();
});

it('shows "Alle toernooien bekijken" in header when there is a tournament winner', function () {
    $user = User::factory()->create();

    // create teams
    $teams = [];
    for ($i = 1; $i <= 8; $i++) {
        $t = Team::create(['name' => "Team $i", 'city' => 'City', 'coach_name' => "Coach $i", 'user_id' => $user->id]);
        $teams[] = $t;
    }

    $comp = Competition::create(['name' => 'Winner Cup']);

    // create some matches including a final with scores
    // Quarterfinals
    for ($i = 0; $i < 8; $i += 2) {
        Matche::create(['team1_id' => $teams[$i]->id, 'team2_id' => $teams[$i+1]->id, 'team1_score' => null, 'team2_score' => null, 'field' => 'TBD', 'referee_id' => $user->id, 'time' => now(), 'round' => 'Quarterfinal', 'competition_id' => $comp->id]);
    }
    // Semifinals
    Matche::create(['team1_id' => $teams[0]->id, 'team2_id' => $teams[1]->id, 'team1_score' => null, 'team2_score' => null, 'field' => 'TBD', 'referee_id' => $user->id, 'time' => now(), 'round' => 'Semifinal', 'competition_id' => $comp->id]);
    Matche::create(['team1_id' => $teams[2]->id, 'team2_id' => $teams[3]->id, 'team1_score' => null, 'team2_score' => null, 'field' => 'TBD', 'referee_id' => $user->id, 'time' => now(), 'round' => 'Semifinal', 'competition_id' => $comp->id]);

    // Final with a winner
    Matche::create(['team1_id' => $teams[0]->id, 'team2_id' => $teams[2]->id, 'team1_score' => 2, 'team2_score' => 1, 'field' => 'TBD', 'referee_id' => $user->id, 'time' => now(), 'round' => 'Final', 'competition_id' => $comp->id]);

    $response = $this->get('/');
    $response->assertSee('Alle toernooien bekijken');
});
