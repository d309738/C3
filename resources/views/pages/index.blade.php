<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Homepage</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-blue-500 text-white min-h-screen">

<header class="p-4 flex justify-between items-center bg-blue-700">
    <h1 class="text-2xl font-bold">Mijn Website</h1>
    <nav>
        <a href="{{ route('home') }}" class="mr-4">Home</a>
        @auth
            <a href="{{ route('teams.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Teams
                </a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="mr-4">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </nav>
</header>

<main class="p-6">
    <h2 class="text-xl font-semibold mb-4">Welkom op de homepage!</h2>
    <p>Klik op "Mijn Teams" in de header om je teams te bekijken of te beheren als je ingelogd bent.</p>
</main>

</body>
</html>
