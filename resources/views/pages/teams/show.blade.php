<x-layouts.app>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-2">{{ $team->name }} ({{ $team->city }})</h1>
        <p><strong>Coach:</strong> {{ $team->coach_name }}</p>

        <h3 class="mt-4 font-semibold">Spelers ({{ $team->players->count() }})</h3>
        <ul class="list-disc list-inside">
            @foreach($team->players as $player)
                <li>{{ $player->name }}</li>
            @endforeach
        </ul>

        <a href="{{ route('teams.index') }}" class="mt-4 inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Terug naar overzicht
        </a>
        <a href="{{ route('home') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Terug naar homepage
        </a>
    </div>
</x-layouts.app>
