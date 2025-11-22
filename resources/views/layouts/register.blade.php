<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance

</head>


<body class="font-sans text-gray-900 antialiased">
<div class="min-h-screen flex flex-col sm:justify-center  pt-6 sm:pt-0 bg-neutral-100 dark:bg-neutral-800">

    <div class="flex flex-col items-center mt-10">
        <a href="/" wire:navigate>
            <x-application-logo/>
        </a>
    </div>

    <flux:card class="space-y-6 m-10">
        <div class="mb-4">
            <div class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </flux:card>
</div>
@fluxScripts

</body>
</html>
