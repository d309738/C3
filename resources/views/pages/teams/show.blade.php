<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $team->name }} ({{ $team->city }})</h1>

        <p class="mb-2"><strong>Coach:</strong> {{ $team->coach_name }}</p>

        <h3 class="font-semibold mt-4 mb-2">Spelers ({{ $team->players->count() }})</h3>
        <ul class="list-disc list-inside mb-4">
            @foreach($team->players as $player)
                <li>{{ $player->name }}</li>
            @endforeach
        </ul>

        <a href="{{ route('teams.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Terug naar overzicht</a>
    </div>
</x-app-layout>
