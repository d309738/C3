<x-layouts.app>
  <div class="max-w-6xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Interactieve 8-team bracket demo</h1>
    <p class="mb-6 text-gray-300">Klik op een team om die wedstrijd te winnen en zien hoe ze doorstromen naar de volgende ronde.</p>

    <x-bracket :teams="$teams" />

    <div class="mt-6 text-sm text-gray-400">Voorbeeldteams: <strong>{{ implode(', ', $teams) }}</strong></div>
  </div>
</x-layouts.app>
