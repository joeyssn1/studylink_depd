{{-- views/components/layout.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <title>{{ $title ?? 'StudyLink' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen flex flex-col text-gray-800">

    <x-header />

    <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8">
        {{ $slot }}
    </main>

    <x-footer />

</body>
</html>
