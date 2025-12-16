<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matche extends Model
{
    use HasFactory;

    protected $fillable = [
        'team1_id',
        'team2_id',
        'team1_score',
        'team2_score',
        'field',
        'referee_id',
        'time',
    ];

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function referee()
    {
        return $this->belongsTo(User::class, 'referee_id');
    }

    // Alias relationships and attribute accessors to match controller expectations
    public function teamA()
    {
        return $this->team1();
    }

    public function teamB()
    {
        return $this->team2();
    }

    // score_a / score_b accessors map to team1_score / team2_score
    public function getScoreAAttribute()
    {
        return $this->team1_score;
    }

    public function getScoreBAttribute()
    {
        return $this->team2_score;
    }

    // Mutators so assigning $model->score_a persists to team1_score column
    public function setScoreAAttribute($value)
    {
        $this->attributes['team1_score'] = $value;
    }

    public function setScoreBAttribute($value)
    {
        $this->attributes['team2_score'] = $value;
    }

    // team_a_id / team_b_id accessors map to team1_id / team2_id
    public function getTeamAIdAttribute()
    {
        return $this->team1_id;
    }

    public function getTeamBIdAttribute()
    {
        return $this->team2_id;
    }

}
