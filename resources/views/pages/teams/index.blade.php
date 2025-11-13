<x-app-layout>
    <div class="container mx-auto p-6">

        {{-- Succesmelding --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <div class="flex gap-2">
                <h1 class="text-2xl font-bold">Mijn Teams</h1>
                <!-- Terug naar homepage knop -->
                <a href="{{ route('home') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Terug naar homepage
                </a>
            </div>

            <a href="{{ route('teams.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Nieuw Team
            </a>
        </div>

        @if($teams->isEmpty())
            <p>Je hebt nog geen teams aangemaakt.</p>
        @else
            <div class="grid gap-4">
                @foreach($teams as $team)
                    <div class="border rounded p-4 shadow">
                        <h2 class="text-xl font-semibold">{{ $team->name }}</h2>
                        <p>Coach: {{ $team->coach_name }}</p>
                        <p>Aantal spelers: {{ $team->players->count() }}</p>

                        <ul class="mt-2 list-disc list-inside">
                            @foreach($team->players as $player)
                                <li>{{ $player->name }}</li>
                            @endforeach
                        </ul>

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
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>

