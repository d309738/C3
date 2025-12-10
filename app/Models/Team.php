<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city', 'coach_name', 'user_id'];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function competitions()
    {
        return $this->belongsToMany(Competition::class, 'competition_team');
    }
    public function matchesAsTeam1()
    {
        return $this->hasMany(Matche::class, 'team1_id');
    }
    public function matchesAsTeam2()
    {
        return $this->hasMany(Matche::class, 'team2_id');
    }
}
