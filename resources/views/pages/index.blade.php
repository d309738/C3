<x-layouts.app>
    <div class="flex justify-between">
        <div class="border w-[300px] shadow-md m-5">
            <h1 class="bg-black text-white p-5">top 5 teams:</h1>
            @php $i = 1; @endphp
            <ul class="m-5">
                @foreach ($top5teams as $team)
                    <li class="flex justify-between mb-2">
                        <p>{{$i++}}. </p>
                        <p>{{ $team->name }}</p>
                        <p>{{ $team->points }} pts</p>
                    </li>

                @endforeach
            </ul>
        </div>
        <img class="w-[500px]" src="https://images.nu.nl/m/bqxxk1raoi7x_wd854/0/205/4280/2408/oranje-speelt-gelijk-tegen-duitsland-in-vermakelijke-nations-league-wedstrijd.jpg" alt="">
    </div>

    <div class="flex justify-between mt-20">
        <div class="w-[500px] border shadow-md m-5">
            <h2 class="bg-black text-white text-center p-4">Wedstrijdschema</h2>
            <ul class="p-4">
                <li class="flex justify-between">
                    <p>Naam</p>
                    <p>tijd</p>
                    <p>field</p>
                    <p>Naam</p>
                </li>
                <li><button id="openModal" class="bg-black text-white mt-4 flex mx-auto px-4 py-2 rounded">Add Team</button></li>
            </ul>
        </div>
        @auth
        <div id="modal" class="fixed inset-0 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded shadow-lg">
                <h2 class="text-xl font-bold mb-4">Add Team</h2>
                <form action="{{route('matche.store')}}" method="POST" class="space-y-4">
                    @csrf
                    <label for="">Team 1:</label>
                    <select name="team1_id" id="team1_id">
                        @foreach($teams as $team)
                            <option value="{{$team->id}}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                    <label for="">Team 2:</label>
                    <select name="team2_id" id="team2_id">
                        @foreach($teams as $team)
                            <option value="{{$team->id}}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="field" id="field" class="border p-2 w-full rounded" placeholder="Field">
                    <input type="hidden" name='referee_id' id="referee_id" value="{{ auth()->id() }}">
                    <input type="text" name="time" id="time" class="border p-2 w-full rounded" placeholder="Time">
                    <div class="flex justify-end space-x-2">
                        <button type="button" id="hideFormBtn" class="px-4 py-2 border rounded">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
        @endauth

        @auth
            <div class="w-[200px] border shadow-md m5">
            <h2 class="bg-black text-white text-center p-4">Mijn team:</h2>
            <ul class="px-10 py-4">
                @php $i = 1; @endphp
                @foreach ($players as $player)
                    <li class="flex justify-between mb-2">
                        <p>{{$i++}}. </p>
                        <p>{{ $player->name }}</p>
                    </li>

                @endforeach
            </ul>
        </div>
        @endauth

    </div>
    <script>
        const showBtn = document.getElementById('openModal');
        const hideBtn = document.getElementById('hideFormBtn');
        const modal = document.getElementById('modal');

        // Toon het form
        showBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        // Verberg het form
        hideBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    </script>

</x-layouts.app>
