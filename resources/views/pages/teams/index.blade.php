<x-app-layout>
    <div class="container mx-auto p-6">

        <h1 class="text-2xl font-bold mb-6">Teams</h1>

        @auth
            <a href="{{ route('teams.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">
                Nieuw Team
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

                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('teams.show', $team->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded">
                                Bekijk
                            </a>

                            @auth
                                <a href="{{ route('teams.edit', $team->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Bewerk</a>

                                <form action="{{ route('teams.destroy', $team->id) }}" method="POST" onsubmit="return confirm('Weet je zeker?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Verwijder</button>
                                </form>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mt-4 inline-block">
            Terug naar homepage
        </a>
    </div>
</x-app-layout>
