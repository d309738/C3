<x-layouts.app>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Toernooi Genereren</h1>

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
                    // If generating a knockout bracket, render the exact bracket image
                    if (payload && payload.format === 'knockout') {
                        // Ensure styles for overlay exist
                        if (!document.getElementById('bracket-overlay-styles')) {
                            const s = document.createElement('style');
                            s.id = 'bracket-overlay-styles';
                            s.textContent = `
                                .bracket-image-container{position:relative;display:inline-block;max-width:100%;}
                                .bracket-image-container img{display:block;max-width:100%;height:auto}
                                .bracket-slot{position:absolute;color:white;font-weight:600;text-shadow:0 1px 2px rgba(0,0,0,.7);background:rgba(8,20,35,.5);padding:3px 6px;border-radius:4px;white-space:nowrap;transform:translate(-50%,-50%);font-size:14px}
                            `;
                            document.head.appendChild(s);
                        }

                        // Create container
                        resultEl.innerHTML = '';
                        const wrap = document.createElement('div');
                        wrap.className = 'bracket-image-container';

                        // Use the exact image placed at public/images/knockout-template.png
                        const img = document.createElement('img');
                        img.alt = 'Knockout bracket';
                        img.src = '/images/knockout-template.png';
                        img.onload = () => {
                            const naturalW = img.naturalWidth || 1365;
                            const naturalH = img.naturalHeight || 768;

                            // Helper to convert pixel positions (for 1365x768) to percent values
                            const pxToPercent = (x, y) => ({ left: (x / 1365 * 100) + '%', top: (y / 768 * 100) + '%' });

                            // Predefined positions (px coords mapped to template image)
                            // Mapping for 4 quarterfinal matches (index 0..3). You can tweak these values.
                            const matchPositions = [
                                // Match 0: left-top
                                { t1: pxToPercent(140, 115), t2: pxToPercent(140, 150) },
                                // Match 1: left-middle
                                { t1: pxToPercent(140, 285), t2: pxToPercent(140, 320) },
                                // Match 2: right-middle
                                { t1: pxToPercent(1200, 285), t2: pxToPercent(1200, 320) },
                                // Match 3: right-bottom
                                { t1: pxToPercent(1200, 455), t2: pxToPercent(1200, 490) },
                            ];

                            // Ensure we have exactly 4 quarterfinal matches; otherwise fallback to simple list
                            const quarterMatches = data.matches.filter(m => (m.round || '').toLowerCase().includes('quarter'));
                            if (quarterMatches.length >= 4) {
                                // Place teams
                                quarterMatches.slice(0,4).forEach((m, idx) => {
                                    const pos = matchPositions[idx];
                                    const name1 = m.team1 ? m.team1.name : (m.team1_id ? ('Team#'+m.team1_id) : '(TBD)');
                                    const name2 = m.team2 ? m.team2.name : (m.team2_id ? ('Team#'+m.team2_id) : '(TBD)');

                                    const el1 = document.createElement('div');
                                    el1.className = 'bracket-slot';
                                    el1.style.left = pos.t1.left;
                                    el1.style.top = pos.t1.top;
                                    el1.textContent = name1;
                                    wrap.appendChild(el1);

                                    const el2 = document.createElement('div');
                                    el2.className = 'bracket-slot';
                                    el2.style.left = pos.t2.left;
                                    el2.style.top = pos.t2.top;
                                    el2.textContent = name2;
                                    wrap.appendChild(el2);
                                });

                                // Append image after positioning elements (image z-index lower)
                                wrap.appendChild(img);
                                resultEl.appendChild(wrap);

                                // Add a small help text
                                const note = document.createElement('div');
                                note.className = 'text-sm text-gray-400 mt-2';
                                // note.textContent = "Gebruik de exacte foto: plaats het bestand op 'public/images/knockout-template.png' als het plaatje niet zichtbaar is.";
                                resultEl.appendChild(note);
                            } else {
                                // fallback to basic list view if structure unexpected
                                resultEl.innerHTML = '<div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded">Kon geen passende knockout-structuur vinden; toon als lijst.</div>';
                                // Simple list rendering
                                const ul = document.createElement('ul');
                                ul.className = 'list-disc list-inside mt-2';
                                data.matches.forEach(m => {
                                    const li = document.createElement('li');
                                    const t1 = m.team1 ? m.team1.name : (m.team1_id ? ('Team#'+m.team1_id) : '(TBD)');
                                    const t2 = m.team2 ? m.team2.name : (m.team2_id ? ('Team#'+m.team2_id) : '(TBD)');
                                    const time = m.time ? ` — ${m.time}` : '';
                                    li.textContent = `${(m.round ? m.round + ': ' : '')}${t1} vs ${t2}${time}`;
                                    ul.appendChild(li);
                                });
                                resultEl.appendChild(ul);
                            }
                        };

                        img.onerror = () => {
                            resultEl.innerHTML = '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">Kon bracket-beeld niet laden. Zorg dat het bestand bestaat op <code>public/images/knockout-template.png</code>.</div>';
                        };

                    } else {
                        // Default rendering: by rounds
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
                    }
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
