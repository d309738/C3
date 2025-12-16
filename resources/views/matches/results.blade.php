<x-layouts.app>

<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto bg-white rounded shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Alle resultaten</h1>

        @if($matches->isEmpty())
            <p class="text-gray-600">Er zijn nog geen gespeelde wedstrijden.</p>
        @else
            <ul class="space-y-4">
                @foreach($matches as $match)
                    <li class="flex justify-between items-center p-3 border rounded">
                        <div>
                            <div class="font-semibold">{{ $match->team1?->name ?? 'Team A' }} <span class="text-xl">{{ $match->score_a }}</span></div>
                            <div class="text-sm text-gray-500">vs {{ $match->team2?->name ?? 'Team B' }} <span class="text-xl">{{ $match->score_b }}</span></div>
                        </div>
                        <div class="text-sm text-gray-600">{{ $match->time }} • {{ $match->field }}</div>
                        <div>
                            <a href="{{ route('matches.view', ['match' => $match->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">View</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-6">
            <a href="{{ route('home') }}" class="text-blue-600">&larr; Terug naar home</a>
        </div>
    </div>
</div>

</x-layouts.app>

