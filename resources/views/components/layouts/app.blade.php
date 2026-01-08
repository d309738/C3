<!DOCTYPE html>
<html lang="en">
<head>
    <x-head/>
</head>
<body class="flex flex-col min-h-screen">
    <x-header/>
    <main class="flex-1 p-40">
        {{ $slot }}
    </main>
    <x-footer/>

    {{-- Allow pages to push scripts --}}
    @stack('scripts')
</body>
</html>
