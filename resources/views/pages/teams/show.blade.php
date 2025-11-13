@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $team->name }} ({{ $team->city }})</h1>
    <p><strong>Coach:</strong> {{ $team->coach_name }}</p>

    <h3>Spelers ({{ $team->players->count() }})</h3>
    <ul>
        @foreach($team->players as $player)
            <li>{{ $player->name }}</li>
        @endforeach
    </ul>

    <a href="{{ route('teams.index') }}" class="btn btn-secondary">Terug naar overzicht</a>
</div>
@endsection
