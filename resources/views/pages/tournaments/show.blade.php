<x-layouts.app>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $competition->name }}</h1>

        @if($matches->isEmpty())
            <div class="text-gray-600">Geen wedstrijden voor dit toernooi.</div>
        @else
            @php
                $isKnockout = $matches->has('Quarterfinal') || $matches->has('Semifinal') || $matches->has('Final');
            @endphp

            @if($isKnockout)
                <div id="bracket-root" class="flex gap-8">
                    {{-- Quarterfinals --}}
                    <div class="w-1/3" id="quarter-column">
                        <h3 class="font-semibold">Quarterfinals</h3>
                        <ul class="list-inside mt-2">
                            @foreach($matches['Quarterfinal'] ?? [] as $m)
                                <li class="mb-2 match-row" data-match-id="{{ $m->id }}" data-round="Quarterfinal">
                                    @php
                                        $t1 = $m->team1 ? $m->team1->name : '(TBD)';
                                        $t2 = $m->team2 ? $m->team2->name : '(TBD)';
                                        $score1 = isset($m->team1_score) ? $m->team1_score : '';
                                        $score2 = isset($m->team2_score) ? $m->team2_score : '';
                                    @endphp
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1">{{ $t1 }} vs {{ $t2 }}</div>
                                        <div class="flex items-center space-x-1">
                                            @auth
                                                <input type="number" min="0" class="score-input" data-team="1" value="{{ $score1 }}" />
                                                <span>:</span>
                                                <input type="number" min="0" class="score-input" data-team="2" value="{{ $score2 }}" />
                                                <button class="btn btn-sm btn-success save-score">Save</button>
                                                <form action="{{ route('matche.destroy', $m) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger delete-match">Delete</button>
                                                </form>
                                            @else
                                                <div class="text-sm text-gray-500">{{ $score1 }} : {{ $score2 }}</div>
                                            @endauth
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Semifinals --}}
                    <div class="w-1/3" id="semi-column">
                        <h3 class="font-semibold">Semifinals</h3>
                        <ul class="list-inside mt-2">
                            @foreach($matches['Semifinal'] ?? [] as $m)
                                <li class="mb-2 match-row" data-match-id="{{ $m->id }}" data-round="Semifinal">
                                    @php
                                        $t1 = $m->team1 ? $m->team1->name : '(TBD)';
                                        $t2 = $m->team2 ? $m->team2->name : '(TBD)';
                                        $score1 = isset($m->team1_score) ? $m->team1_score : '';
                                        $score2 = isset($m->team2_score) ? $m->team2_score : '';
                                    @endphp
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1">{{ $t1 }} vs {{ $t2 }}</div>
                                        <div class="flex items-center space-x-1">
                                            @auth
                                                <input type="number" min="0" class="score-input" data-team="1" value="{{ $score1 }}" />
                                                <span>:</span>
                                                <input type="number" min="0" class="score-input" data-team="2" value="{{ $score2 }}" />
                                                <button class="btn btn-sm btn-success save-score">Save</button>
                                                <form action="{{ route('matche.destroy', $m) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger delete-match">Delete</button>
                                                </form>
                                            @else
                                                <div class="text-sm text-gray-500">{{ $score1 }} : {{ $score2 }}</div>
                                            @endauth
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Final --}}
                    <div class="w-1/3" id="final-column">
                        <h3 class="font-semibold">Final</h3>
                        <ul class="list-inside mt-2">
                            @foreach($matches['Final'] ?? [] as $m)
                                <li class="mb-2 match-row" data-match-id="{{ $m->id }}" data-round="Final">
                                    @php
                                        $t1 = $m->team1 ? $m->team1->name : '(TBD)';
                                        $t2 = $m->team2 ? $m->team2->name : '(TBD)';
                                        $score1 = isset($m->team1_score) ? $m->team1_score : '';
                                        $score2 = isset($m->team2_score) ? $m->team2_score : '';
                                    @endphp
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1">{{ $t1 }} vs {{ $t2 }}</div>
                                        <div class="flex items-center space-x-1">
                                            @auth
                                                <input type="number" min="0" class="score-input" data-team="1" value="{{ $score1 }}" />
                                                <span>:</span>
                                                <input type="number" min="0" class="score-input" data-team="2" value="{{ $score2 }}" />
                                                <button class="btn btn-sm btn-success save-score">Save</button>
                                                <form action="{{ route('matche.destroy', $m) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger delete-match">Delete</button>
                                                </form>
                                            @else
                                                <div class="text-sm text-gray-500">{{ $score1 }} : {{ $score2 }}</div>
                                            @endauth
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @else
                @foreach($matches as $round => $ms)
                    <h2 class="text-xl font-semibold mt-4">{{ $round }}</h2>
                    <ul class="list-disc list-inside mb-4">
                        @foreach($ms as $m)
                            <li data-match-id="{{ $m->id }}">
                                @php
                                    $t1 = $m->team1 ? $m->team1->name : '(TBD)';
                                    $t2 = $m->team2 ? $m->team2->name : '(TBD)';
                                    $score = (isset($m->team1_score) && isset($m->team2_score)) ? " — {$m->team1_score}:{$m->team2_score}" : '';
                                @endphp
                                {{ $t1 }} vs {{ $t2 }}{{ $score }}
                                @auth
                                    <form class="inline-block delete-form" action="{{ route('matche.destroy', $m) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger delete-match">Delete</button>
                                    </form>
                                @endauth
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            @endif
        @endif

        <a href="{{ route('tournaments.index') }}" class="btn btn-secondary">Terug</a>

        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            async function saveMatchScore(matchId, s1, s2) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch(`/api/matches/${matchId}/score`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ team1_score: s1, team2_score: s2 })
                });

                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    throw new Error(err.message || 'Failed to save');
                }

                return res.json();
            }

            document.querySelectorAll('.save-score').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const row = this.closest('.match-row');
                    const matchId = row.dataset.matchId;
                    const s1 = row.querySelector('.score-input[data-team="1"]').value || 0;
                    const s2 = row.querySelector('.score-input[data-team="2"]').value || 0;

                    this.disabled = true;
                    try {
                        const r = await saveMatchScore(matchId, parseInt(s1), parseInt(s2));
                        // update UI
                        row.querySelectorAll('.score-input').forEach(i => i.value = '');
                        const span = document.createElement('div');
                        span.className = 'text-sm text-green-600';
                        span.textContent = 'Opgeslagen';
                        row.appendChild(span);

                        // Refresh bracket section by reloading the page fragment (simple approach)
                        setTimeout(() => location.reload(), 600);
                    } catch (err) {
                        alert('Fout bij opslaan: ' + err.message);
                    } finally {
                        this.disabled = false;
                    }
                });
            });

            // Delete match handler
            document.querySelectorAll('.delete-match').forEach(btn => {
                btn.addEventListener('click', async function () {
                    if (!confirm('Weet je zeker dat je deze wedstrijd wil verwijderen?')) return;
                    const row = this.closest('.match-row') || this.closest('li');
                    const matchId = row?.dataset?.matchId;
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    this.disabled = true;
                    try {
                        const res = await fetch(`/matche/${matchId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        });

                        if (!res.ok) {
                            const err = await res.json().catch(() => ({}));
                            throw new Error(err.message || 'Failed to delete');
                        }

                        // remove the row
                        if (row) row.remove();
                    } catch (err) {
                        alert('Fout bij verwijderen: ' + err.message);
                    } finally {
                        this.disabled = false;
                    }
                });
            });

        });
        </script>
        @endpush
    </div>
</x-layouts.app>
