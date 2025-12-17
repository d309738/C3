<x-layouts.app>

<div class="container mx-auto p-6">
    <div class="max-w-md mx-auto bg-white rounded shadow p-6 text-center">
        <h1 class="text-2xl font-bold mb-4">Results saved!</h1>
        <p class="mb-6">The match result was saved and team points were updated.</p>

        <div class="flex justify-center space-x-3">
            <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Back to homepage</a>
            <a href="{{ url()->previous() }}" class="bg-gray-200 hover:bg-gray-300 text-black px-4 py-2 rounded">Go back</a>
        </div>
    </div>
</div>

</x-layouts.app>

