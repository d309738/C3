<x-layouts.app>
    <div class="flex justify-between">
        <div class="border w-[300px] shadow-md m-5">
            <h1 class="bg-black text-white p-5">top 5 teams:</h1>
            <ul class="m-5">
                <li>1.</li>
                <li>2.</li>
                <li>3.</li>
                <li>4.</li>
                <li>5.</li>
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
                    <p>Naam</p>
                </li>
                <li class="flex justify-between">
                    <p>Naam</p>
                    <p>tijd</p>
                    <p>Naam</p>
                </li>
                <li class="flex justify-between">
                    <p>Naam</p>
                    <p>tijd</p>
                    <p>Naam</p>
                </li>
            </ul>
        </div>
        @auth
            <div class="w-[200px] border shadow-md m5">
            <h2 class="bg-black text-white text-center p-4">Mijn team:</h2>
            <ul class="px-10 py-4">
                <li>Naam</li>
                <li>Naam</li>
                <li>Naam</li>
                <li>Naam</li>
                <li>Naam</li>
                <li>Naam</li>
            </ul>
        </div>
        @endauth

    </div>


</x-layouts.app>
