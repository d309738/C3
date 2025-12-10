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


        @auth
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
