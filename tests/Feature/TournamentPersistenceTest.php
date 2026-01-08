<?php

use App\Models\Team;
use App\Models\User;
use App\Models\Competition;
use App\Models\Matche;

beforeEach(function () {
    Matche::query()->delete();
    Team::query()->delete();
    Competition::query()->delete();
});

it('persists competition when name provided and assigns competition_id to matches', function () {
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
            'tournament_name' => 'Test Cup',
        ]);

    $response->assertStatus(200);

    $this->assertSame(1, Competition::where('name', 'Test Cup')->count());
    $comp = Competition::where('name', 'Test Cup')->first();
    $this->assertTrue(Matche::where('competition_id', $comp->id)->exists());
});
