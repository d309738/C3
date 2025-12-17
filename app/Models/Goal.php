<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'match_id',
        'player_id',
        'minute'
    ];
    public function matche()
    {
        return $this->belongsTo(Matche::class);
    }
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
