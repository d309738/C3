<?php

use App\Models\Team;
use App\Models\User;
use App\Models\Matche;

beforeEach(function () {
    Matche::query()->delete();
    Team::query()->delete();
});

it('generates a knockout bracket for 8 selected teams', function () {
    $user = User::factory()->create();

    $teams = [];
    for ($i = 1; $i <= 8; $i++) {
        $t = Team::create([
            'name' => "Team $i",
            'city' => 'City',
            'coach_name' => "Coach $i",
            'user_id' => $user->id,
        ]);
        $teams[] = $t->id;
    }

    $response = $this
        ->actingAs($user)
        ->post('/schedule/generate', [
            'team_ids' => $teams,
            'format' => 'knockout',
        ]);

    $response->assertStatus(200);
    $json = $response->json();

    // Expect 7 matches for 8-team single-elimination
    $this->assertCount(7, $json['matches']);

    $qf = collect($json['matches'])->where('round', 'Quarterfinal');
    $this->assertCount(4, $qf);

    // Ensure quarterfinal matches have both teams assigned
    foreach ($qf as $m) {
        $this->assertNotNull($m['team1_id']);
        $this->assertNotNull($m['team2_id']);
    }

    $this->assertSame(7, Matche::count());
});
