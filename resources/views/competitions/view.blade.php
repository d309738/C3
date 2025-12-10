<x-layouts.app>

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Competities Overzicht</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($competitions as $competition)
            <div class="border p-4 rounded shadow hover:shadow-lg">
                <h2 class="text-xl font-bold mb-2">{{ $competition->name }}</h2>
                <p class="mb-2 font-semibold">Ingeschreven teams:</p>
                <ul class="list-disc list-inside text-gray-700">
                    @forelse($competition->teams as $team)
                        <li>{{ $team->name }}</li>
                    @empty
                        <li>Geen teams ingeschreven</li>
                    @endforelse
                </ul>
            </div>
        @endforeach
    </div>
</div>
</x-layouts.app>
