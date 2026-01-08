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

it('allows authenticated user to delete a match', function () {
    $user = User::factory()->create();

    $team1 = Team::create(['name' => 'A', 'city' => 'C', 'coach_name' => 'X', 'user_id' => $user->id]);
    $team2 = Team::create(['name' => 'B', 'city' => 'C', 'coach_name' => 'Y', 'user_id' => $user->id]);

    $comp = Competition::create(['name' => 'Cup']);

    $match = Matche::create([
        'team1_id' => $team1->id,
        'team2_id' => $team2->id,
        'field' => 'TBD',
        'referee_id' => $user->id,
        'time' => now(),
        'round' => 'Friendly',
        'competition_id' => $comp->id,
    ]);

    $this->actingAs($user)
        ->deleteJson("/matche/{$match->id}")
        ->assertStatus(200);

    $this->assertDatabaseMissing('matches', ['id' => $match->id]);
});

it('does not allow guests to delete a match', function () {
    $user = User::factory()->create();

    $team1 = Team::create(['name' => 'A', 'city' => 'C', 'coach_name' => 'X', 'user_id' => $user->id]);
    $team2 = Team::create(['name' => 'B', 'city' => 'C', 'coach_name' => 'Y', 'user_id' => $user->id]);

    $comp = Competition::create(['name' => 'Cup']);

    $match = Matche::create([
        'team1_id' => $team1->id,
        'team2_id' => $team2->id,
        'field' => 'TBD',
        'referee_id' => $user->id,
        'time' => now(),
        'round' => 'Friendly',
        'competition_id' => $comp->id,
    ]);

    $this->delete("/matche/{$match->id}")
        ->assertRedirect('/login');
});
