<x-layouts.app>

<div class="container mx-auto p-6">
    <div class="max-w-lg mx-auto bg-white rounded shadow p-6 text-center">
        <h1 class="text-2xl font-bold mb-4">Match Result</h1>

        <div class="text-lg mb-4">
            <div class="font-semibold">{{ $match->teamA?->name ?? $match->team1?->name }} <span class="text-2xl">{{ $match->score_a ?? '–' }}</span></div>
            <div class="text-sm text-gray-500 mb-2">vs</div>
            <div class="font-semibold">{{ $match->teamB?->name ?? $match->team2?->name }} <span class="text-2xl">{{ $match->score_b ?? '–' }}</span></div>
        </div>

        <p class="text-sm text-gray-600 mb-4">Field: {{ $match->field }} • Time: {{ $match->time }}</p>

        <div class="flex justify-center space-x-3">
            <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Back to homepage</a>
            @auth
                <a href="{{ route('matches.result.form', ['match' => $match->id]) }}" class="bg-gray-200 hover:bg-gray-300 text-black px-4 py-2 rounded">Edit result</a>
            @endauth
        </div>
    </div>
</div>

</x-layouts.app>

