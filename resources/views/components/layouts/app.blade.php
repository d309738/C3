<!DOCTYPE html>
<html lang="en">
<head>
    <x-head/>
</head>
<body class="flex flex-col min-h-screen">
    <x-header/>
    <main class="flex-1 px-50 py-20">
        {{ $slot }}
    </main>
    <x-footer/>
</body>
</html>
