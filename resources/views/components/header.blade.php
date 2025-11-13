<header class="bg-blue-500 text-yellow-100 flex justify-between items-center p-10 min-h-[100px]">
    <nav class="flex gap-[20px]">
        <a href="#" class="text-yellow-200 font-bold text-4xl">Home</a>
        <a href="#" class="text-yellow-200 font-bold text-4xl">Teams</a>
        <a href="#" class="text-yellow-200 font-bold text-4xl">Speelschema</a>
    </nav>
    @guest
    <div>
        <a href="{{ route('login') }}" class="text-yellow-200 font-bold text-4xl">Login</a> |
        <a href="{{ route('register') }}" class="text-yellow-200 font-bold text-4xl">Register</a>
    </div>
    @endguest

</header>
