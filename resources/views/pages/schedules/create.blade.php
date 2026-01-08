<x-layouts.app>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Toernooi genereren</h1>

        <div class="mb-4">
            <p class="text-sm text-gray-600 mb-2">Selecteer maximaal 8 teams (of laat leeg om 8 teams random te kiezen). Kies type: <strong>Round-robin</strong> of <strong>Knockout</strong>.</p>

            <div class="mb-3">
                <label class="block font-medium mb-1">Teams (max 8)</label>
                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-auto border rounded p-2 bg-white">
                    @forelse($teams as $t)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="selected_teams[]" value="{{ $t->id }}" class="team-checkbox">
                            <span>{{ $t->name }} ({{ $t->city }})</span>
                        </label>
                    @empty
                        <div class="text-sm text-gray-500">Geen teams beschikbaar. Maak eerst teams aan.</div>
                    @endforelse
                </div>
            </div>

            <div class="mb-3">
                <label class="block font-medium mb-1">Type schema</label>
                <label class="mr-4"><input type="radio" name="format" value="round-robin" checked> Round-robin</label>
                <label><input type="radio" name="format" value="knockout"> Knockout (enkel eliminatie)</label>
            </div>

            <div class="mb-3">
                <label class="block font-medium mb-1">Naam toernooi (optioneel)</label>
                <input type="text" id="tournament-name" class="form-control" placeholder="Bv. School Cup 2026" />
            </div>

            <div class="mb-3">
                <label class="block font-medium mb-1">Starttijd (optioneel)</label>
                <input type="datetime-local" id="start-time" class="form-control" />
            </div>

            <div class="flex items-center space-x-3">
                <button type="button" id="generate-schedule-btn" class="btn btn-primary" data-url="{{ route('schedule.generate') }}">Genereer en Opslaan</button>
                <button type="button" id="generate-schedule-preview" class="btn btn-secondary">Voorbeeld (preview)</button>
            </div>

            <div id="schedule-result" class="mt-3"></div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Include the same JS used elsewhere for collecting payloads
        const genBtn = document.getElementById('generate-schedule-btn');
        const previewBtn = document.getElementById('generate-schedule-preview');

        function collectSchedulePayload() {
            const selected = Array.from(document.querySelectorAll('.team-checkbox:checked')).map(i => parseInt(i.value));
            const format = document.querySelector('input[name="format"]:checked').value;
            const startTimeEl = document.getElementById('start-time');
            const start = startTimeEl && startTimeEl.value ? startTimeEl.value : null;
            const nameEl = document.getElementById('tournament-name');
            const name = nameEl && nameEl.value ? nameEl.value.trim() : null;
            return { team_ids: selected.length ? selected : null, format, start_time: start, tournament_name: name };
        }

        async function sendScheduleRequest(url, payload, save = true) {
            const resultEl = document.getElementById('schedule-result');
            const meta = document.querySelector('meta[name="csrf-token"]');
            const csrf = meta ? meta.getAttribute('content') : '{{ csrf_token() }}';

            genBtn.disabled = true;
            genBtn.textContent = save ? 'Genereren...' : 'Voorbeeld laden...';
            resultEl.innerHTML = '';

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) {
                    const error = await res.json().catch(() => null);
                    throw new Error((error && error.message) ? error.message : 'Serverfout');
                }

                const data = await res.json();
                if (data && data.matches && data.matches.length) {
                    // Render by rounds
                    const rounds = {};
                    data.matches.forEach(m => {
                        const r = m.round || 'Match';
                        if (!rounds[r]) rounds[r] = [];
                        rounds[r].push(m);
                    });

                    resultEl.innerHTML = '<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">Schema gegenereerd:</div>';
                    Object.keys(rounds).forEach(r => {
                        const h = document.createElement('h3');
                        h.className = 'font-semibold mt-3';
                        h.textContent = r;
                        resultEl.appendChild(h);

                        const ul = document.createElement('ul');
                        ul.className = 'list-disc list-inside';
                        rounds[r].forEach(m => {
                            const li = document.createElement('li');
                            const t1 = m.team1 ? m.team1.name : (m.team1_id ? ('Team#'+m.team1_id) : '(TBD)');
                            const t2 = m.team2 ? m.team2.name : (m.team2_id ? ('Team#'+m.team2_id) : '(TBD)');
                            const time = m.time ? ` — ${m.time}` : '';
                            li.textContent = `${t1} vs ${t2}${time}`;
                            ul.appendChild(li);
                        });
                        resultEl.appendChild(ul);
                    });
                } else if (data && data.message) {
                    resultEl.innerHTML = `<div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded">${data.message}</div>`;
                } else {
                    resultEl.innerHTML = '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">Geen wedstrijden gegenereerd.</div>';
                }
            } catch (err) {
                resultEl.innerHTML = `<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">Fout: ${err.message}</div>`;
            } finally {
                genBtn.disabled = false;
                genBtn.textContent = 'Genereer en Opslaan';
            }
        }

        if (genBtn) {
            genBtn.addEventListener('click', function () {
                const payload = collectSchedulePayload();
                if (!payload.team_ids) {
                    if (!confirm('Er zijn geen teams geselecteerd; er worden 8 teams willekeurig gekozen. Doorgaan?')) return;
                } else if (payload.team_ids.length !== 8) {
                    if (!confirm('U heeft minder of meer dan 8 teams geselecteerd; het systeem vereist precies 8 teams. Doorgaan met de eerste 8 gekozen teams?')) return;
                    payload.team_ids = payload.team_ids.slice(0, 8);
                }
                payload.preview = false;
                sendScheduleRequest(genBtn.dataset.url, payload, true);
            });
        }

        if (previewBtn) {
            previewBtn.addEventListener('click', function () {
                const payload = collectSchedulePayload();
                payload.preview = true;
                sendScheduleRequest(genBtn.dataset.url, payload, false);
            });
        }
    });
    </script>
    @endpush
</x-layouts.app>
