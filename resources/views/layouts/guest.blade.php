{{-- resources/views/layouts/guest.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'FACKATUETE') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 antialiased">
    <div class="min-h-screen flex items-center justify-center">
        {{ $slot }}
    </div>
</body>
</html>
