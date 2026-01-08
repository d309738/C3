<?php

use App\Models\Team;
use App\Models\User;
use App\Models\Matche;

beforeEach(function () {
    // Use delete() to avoid FK/DB driver issues with truncate in tests
    Matche::query()->delete();
    Team::query()->delete();
});

it('generates a round-robin schedule for 8 teams', function () {
    $user = User::factory()->create();

    // create 8 teams
    for ($i = 1; $i <= 8; $i++) {
        Team::create([
            'name' => "Team $i",
            'city' => 'City',
            'coach_name' => "Coach $i",
            'user_id' => $user->id,
        ]);
    }

    $response = $this
        ->actingAs($user)
        ->post('/schedule/generate');

    $response->assertStatus(200);
    $response->assertJsonStructure(['matches']);

    $json = $response->json();
    $this->assertCount(28, $json['matches']); // 8 choose 2 = 28 matches for round-robin

    $this->assertSame(28, Matche::count());
});
