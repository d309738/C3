<x-layouts.app>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Alle Teams</h1>

        <a href="{{ route('home') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mb-4 inline-block">
            Terug naar homepage
        </a>

        @auth
            <a href="{{ route('teams.create') }}" class="bg-blue-500 hover:bg-blue-600 text-black px-4 py-2 rounded mb-4 inline-block">
                Maak een nieuw team
            </a>
        @endauth

        @if($teams->isEmpty())
            <p>Er zijn nog geen teams aangemaakt.</p>
        @else
            <div class="grid gap-4">
                @foreach($teams as $team)
                    <div class="border rounded p-4 shadow">
                        <h2 class="text-xl font-semibold">{{ $team->name }} ({{ $team->city }})</h2>
                        <p>Coach: {{ $team->coach_name }}</p>
                        <p>Aantal spelers: {{ $team->players->count() }}</p>

                        <ul class="mt-2 list-disc list-inside">
                            @foreach($team->players as $player)
                                <li>{{ $player->name }}</li>
                            @endforeach
                        </ul>

                        @auth
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('teams.show', $team->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded">
                                Bekijk
                            </a>

                            <form action="{{ route('teams.destroy', $team->id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je dit team wilt verwijderen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                    Verwijder
                                </button>
                            </form>
                        </div>
                        @endauth
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
