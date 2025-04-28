<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet"/>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>


<body class="min-h-screen bg-neutral-50 dark:bg-neutral-800">
<flux:sidebar sticky stashable
              class="bg-neutral-50 dark:bg-neutral-900 border-r rtl:border-r-0 rtl:border-l border-neutral-200 dark:border-neutral-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>
    <div class="flex justify-center">
        <x-application-logo/>
    </div>


    <flux:input as="button" variant="filled" placeholder="Search..." icon="magnifying-glass"/>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="home" href="{{ route('dashboard') }}" wire:navigate>Home</flux:navlist.item>
        <flux:navlist.item icon="users" badge="" href="{{ route('clientes') }}" wire:navigate>Clientes
        </flux:navlist.item>
        <flux:navlist.item icon="briefcase" href="{{ route('servicos') }}">Servicos</flux:navlist.item>
        <flux:navlist.item icon="wrench-screwdriver" href="{{ route('tecnico') }}">Tecnicos</flux:navlist.item>
        <flux:navlist.item icon="user-group" href="{{ route('usuarios') }}">Usuarios</flux:navlist.item>
    </flux:navlist>
    <flux:spacer/>
    <flux:navlist variant="outline">
        <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle"
                     aria-label="Toggle dark mode"/>
        <flux:navlist.item icon="cog-6-tooth" href="#">Configuracoes</flux:navlist.item>
        <flux:navlist.item icon="information-circle" href="#">FAQ</flux:navlist.item>
    </flux:navlist>
    <flux:dropdown position="top" align="start" class="max-lg:hidden">
        <flux:profile name="{{ auth()->user()->nome }}"/>
        <flux:menu>
            <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>

<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

    <flux:spacer/>

    <flux:dropdown position="top" alignt="start">
        <flux:profile avatar="https://fluxui.dev/img/demo/user.png"/>

        <flux:menu>
            <flux:menu.radio.group>
                <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
                <flux:menu.radio>Truly Delta</flux:menu.radio>
            </flux:menu.radio.group>

            <flux:menu.separator/>

            <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:header>

<flux:main>
    {{ $slot }}
</flux:main>

@fluxScripts

</body>
</html>
