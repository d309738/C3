<x-layouts.app>
<div class="max-w-2xl mx-auto p-4">

    <h1 class="text-2xl font-bold mb-4">Competities inschrijven</h1>

    {{-- Success message --}}
    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error message --}}
    @if(session('error'))
        <div class="bg-red-200 text-red-800 p-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('competitions.registerTeam') }}">
        @csrf

        {{-- Team select --}}
        <label for="team_id" class="block mb-2 font-semibold">Selecteer je team:</label>
        <select name="team_id" id="team_id" class="border p-2 mb-4 w-full">
            @if(isset($teams) && $teams->count() > 0)
                @foreach($teams as $team)
                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            @else
                <option value="">Je hebt nog geen teams</option>
            @endif
        </select>

        {{-- Competities checkboxes --}}
        <h2 class="font-semibold mb-2">Kies competities:</h2>

        @if(isset($competitions) && $competitions->count() > 0)
            @foreach($competitions as $competition)
                <div class="mb-2">
                    <input type="checkbox" name="competition_ids[]" value="{{ $competition->id }}" id="comp-{{ $competition->id }}">
                    <label for="comp-{{ $competition->id }}">{{ $competition->name }}</label>
                </div>
            @endforeach
        @else
            <p>Er zijn nog geen competities beschikbaar.</p>
        @endif

        <button class="bg-blue-500 text-white px-4 py-2 rounded mt-4 hover:bg-blue-600">
            Inschrijven
        </button>
    </form>

</div>
</x-layouts.app>
