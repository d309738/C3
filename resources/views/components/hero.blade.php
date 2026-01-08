<div class="bg-gradient-to-r from-white to-[#fff2f2] dark:from-[#0a0a0a] dark:to-[#1D0002] rounded-lg p-8 lg:p-16 shadow-lg">
    <div class="max-w-4xl mx-auto text-center lg:text-left">
        <h1 class="text-3xl lg:text-5xl font-bold text-[#1b1b18] dark:text-white mb-4">Welkom bij {{ config('app.name') }}</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-6">Vind competities, teams en wedstrijden — alles op één plek. Begin met verkennen of maak direct iets aan.</p>
        <div class="flex justify-center lg:justify-start gap-3">
            <a href="{{ route('competitions.index') }}" class="inline-flex items-center px-6 py-3 bg-[#F53003] hover:bg-[#d43a2a] text-white rounded-md font-semibold shadow-sm">Bekijk competities</a>
            <a href="{{ route('teams.index') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-800 rounded-md font-semibold">Bekijk teams</a>
        </div>
    </div>
</div>
