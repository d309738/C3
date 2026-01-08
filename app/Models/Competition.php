<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    // Welke velden mogen via mass assignment
    protected $fillable = ['name', 'winner'];

    // Relatie: een competitie heeft veel teams via pivot tabel
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'competition_team');
    }

    public function matches()
    {
        return $this->hasMany(\App\Models\Matche::class, 'competition_id');
    }
}
