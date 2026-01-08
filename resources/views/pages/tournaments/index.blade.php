<x-layouts.app>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Alle Toernooien</h1>

        @if($competitions->isEmpty())
            <div class="text-gray-600">Nog geen toernooien beschikbaar.</div>
        @else
            <ul class="space-y-3">
                @foreach($competitions as $c)
                    <li class="p-3 border rounded flex justify-between items-center">
                        <div>
                            <div class="font-semibold">{{ $c->name }}</div>
                            <div class="text-sm text-gray-500">{{ $c->teams_count }} teams</div>
                        </div>
                        <div>
                            <a href="{{ route('tournaments.show', $c) }}" class="btn btn-primary">Bekijk</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-layouts.app>
