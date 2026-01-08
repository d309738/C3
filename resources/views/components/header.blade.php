<header class="bg-blue-500 text-yellow-100 flex justify-between items-center p-6 min-h-[100px]">
    <!-- Logo / Titel -->
    <a href="{{ route('home') }}" class="text-2xl font-bold">
        Schoolvoetbal
    </a>

    <!-- Navigatie -->
    <nav class="flex items-center space-x-4">
        <a href="{{ route('teams.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Teams
        </a>

        <a href="{{ route('competitions.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Competities inschrijven
        </a>

        <a href="{{ route('competitions.view') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
    Competities bekijken
</a>

        <a href="{{ route('schedule.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Toernooi
        </a>

        <a href="{{ route('matches.results') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Bekijk resultaten
        </a>


        @auth
            <!-- Quick submit result: enter match ID and go to form -->
            <form onsubmit="event.preventDefault(); window.location.href='/matches/' + document.getElementById('matchIdInput').value + '/result'" class="inline-flex items-center space-x-2">
                <input id="matchIdInput" type="number" min="1" placeholder="Match ID" class="px-2 py-1 rounded text-black" required />
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">Submit result</button>
            </form>
            <!-- Logout knop -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Logout
                </button>
            </form>
        @else
            <!-- Gasten: Login / Register -->
            <a href="{{ route('login') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Login
            </a>
            <a href="{{ route('register') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                Register
            </a>
        @endauth
    </nav>
</header>
