<x-layouts.app>
    <h1>Schoolvoetbal</h1>
    <div class="flex-column gap-10">
        <div class="flex justify-between">
            <div class="bg-gray-200 p-4">
                <h3>Top 5: </h3>
                <h5>Ajax</h5>
                <h5>Fynord</h5>
                <h5>PSV</h5>
                <h5>Utrecht</h5>
                <h5>AZ</h5>
            </div>
            <div>
                <img src="https://www.ajax.nl/media/2rwemxdz/1819historie.jpg" alt="" class="w-[200px]">
            </div>
        </div>
        <div>
            @auth
    <a href="{{ route('teams.index') }}" class="btn btn-primary">Mijn Teams</a>
@else
    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
@endauth

        </div>
    </div>
</x-layouts.app>
