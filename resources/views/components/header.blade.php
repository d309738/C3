<header class="bg-blue-500 text-yellow-100 flex justify-between items-center p-10 min-h-[100px]">
    <h1 class="text-2xl font-bold">Schoolvoetbal</h1>
    <nav>

        @auth
            <a href="{{ route('teams.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Teams
                </a>
                <a href="{{route ('competitions.index')}}">competitions</a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="{{ route('teams.index')}}">Teams</a>
            <a href="{{ route('login') }}" class="mr-4">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </nav>

</header>
