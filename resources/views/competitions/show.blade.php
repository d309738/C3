<x-layouts.app>

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <h1 class="text-3xl font-bold mb-4">{{ $competition->name }}</h1>

    <h2 class="text-xl font-semibold mt-6">Ingeschreven Teams:</h2>

    <ul class="mt-2 space-y-2">
        @forelse ($competition->teams as $team)
            <li class="p-3 bg-gray-100 rounded">{{ $team->name }}</li>
        @empty
            <p class="text-gray-500">Nog geen teams ingeschreven.</p>
        @endforelse
    </ul>

    @auth
    <a href="{{ route('competitions.register', $competition) }}"
       class="mt-6 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Team inschrijven
    </a>
    @endauth

</div>
</x-layouts.app>
