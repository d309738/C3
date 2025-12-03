@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Schrijf je team in voor {{ $competition->name }}</h1>

@if(session('success'))
    <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-200 text-red-800 p-2 mb-4 rounded">
        {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('competitions.register.store', $competition) }}">
    @csrf
    <label for="team_id" class="block mb-2">Kies een team:</label>
    <select name="team_id" id="team_id" class="border p-2 mb-4 w-full">
        @foreach($teams as $team)
            <option value="{{ $team->id }}">{{ $team->name }}</option>
        @endforeach
    </select>
    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Inschrijven</button>
</form>
@endsection
