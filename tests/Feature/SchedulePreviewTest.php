<?php

use App\Models\Team;
use App\Models\User;
use App\Models\Matche;

beforeEach(function () {
    Matche::query()->delete();
    Team::query()->delete();
});

it('preview does not persist matches', function () {
    $user = User::factory()->create();

    // Create 8 teams
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
        ->post('/schedule/generate', [
            'format' => 'round-robin',
            'preview' => true,
        ]);

    $response->assertStatus(200);
    $json = $response->json();
    $this->assertCount(28, $json['matches']);

    // Ensure no matches were persisted
    $this->assertSame(0, Matche::count());
});
