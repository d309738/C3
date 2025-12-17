<x-layouts.app>

<div class="container">
    <h1>Submit result for match #{{ $match->id }}</h1>

    <p>
        <strong>Team A:</strong> {{ $match->teamA?->name ?? $match->team1?->name }}<br>
        <strong>Team B:</strong> {{ $match->teamB?->name ?? $match->team2?->name }}
    </p>

    <form method="POST" action="{{ route('matches.result', ['match' => $match->id]) }}">
        @csrf

        <div class="mb-3">
            <label for="score_a" class="form-label">Score Team A</label>
            <input id="score_a" name="score_a" type="number" min="0" required class="form-control" value="{{ old('score_a', $match->score_a ?? '') }}">
        </div>

        <div class="mb-3">
            <label for="score_b" class="form-label">Score Team B</label>
            <input id="score_b" name="score_b" type="number" min="0" required class="form-control" value="{{ old('score_b', $match->score_b ?? '') }}">
        </div>

        <button class="btn btn-primary" type="submit">Save result</button>
    </form>
</div>

</x-layouts.app>

