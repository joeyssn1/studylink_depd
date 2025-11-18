<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'StudyLink' }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-white">

    <x-header />

    <main>
        {{ $slot }}
    </main>

    <x-footer />

</body>
</html>
