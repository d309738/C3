@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-10">

    <h1 class="text-3xl font-bold mb-6">Competities</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($competitions as $competition)
            <div class="bg-white p-5 rounded shadow">
                <h2 class="text-xl font-semibold">{{ $competition->name }}</h2>

                <p class="text-gray-600 mt-2">
                    Teams: {{ $competition->teams->count() }}
                </p>

                <a href="{{ route('competitions.show', $competition) }}"
                   class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Bekijken
                </a>
            </div>
        @endforeach
    </div>

</div>
@endsection
