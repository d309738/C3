<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Nieuw Team Aanmaken</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teams.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Teamnaam</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label>Stad</label>
                <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
            </div>

            <div class="mb-3">
                <label>Coach naam</label>
                <input type="text" name="coach_name" class="form-control" value="{{ old('coach_name') }}" required>
            </div>

            <div id="players-wrapper" class="mb-3">
                <label>Spelers (minimaal 1)</label>
                <div class="player-row mb-2">
                    <input type="text" name="players[]" class="form-control mb-2" placeholder="Naam speler" required>
                </div>
            </div>

            <button type="button" id="add-player" class="btn btn-secondary mb-3">Speler toevoegen</button>

            <div>
                <button type="submit" class="btn btn-success">Opslaan</button>
                <a href="{{ route('teams.index') }}" class="btn btn-secondary">Annuleren</a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('add-player').addEventListener('click', function () {
            const wrapper = document.getElementById('players-wrapper');
            const div = document.createElement('div');
            div.className = 'player-row mb-2';
            div.innerHTML = `
                <input type="text" name="players[]" class="form-control mb-1" placeholder="Naam speler" required>
                <button type="button" class="btn btn-sm btn-danger remove-player">Verwijder</button>
            `;
            wrapper.appendChild(div);

            div.querySelector('.remove-player').addEventListener('click', function () {
                div.remove();
            });
        });
    });
    </script>
</x-app-layout>
