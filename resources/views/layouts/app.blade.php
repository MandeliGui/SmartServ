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


    <flux:navlist variant="outline">
        <flux:navlist.item icon="home" href="{{ route('dashboard') }}" wire:navigate>Home</flux:navlist.item>
        <flux:navlist.item icon="users" badge="" href="{{ route('clientes') }}" wire:navigate>Clientes</flux:navlist.item>
        <flux:navlist.item icon="store" badge="" href="{{ route('fornecedores') }}" wire:navigate>Fornecedores</flux:navlist.item>
        <flux:navlist.item icon="briefcase" href="{{ route('servicos') }}" wire:navigate>Serviços</flux:navlist.item>
        <flux:navlist.item icon="wrench-screwdriver" href="{{ route('tecnico') }}" wire:navigate>Técnicos</flux:navlist.item>
        <flux:navlist.item icon="banknotes" href="{{ route('formas-pagamento') }}" wire:navigate>Formas Pagamento</flux:navlist.item>
        <flux:navlist.item icon="tag" href="{{ route('grupo-cliente') }}" wire:navigate>Grupo Clientes</flux:navlist.item>
        <flux:navlist.item icon="wrench-screwdriver" href="{{ route('materiais') }}" wire:navigate>Materiais</flux:navlist.item>
        <flux:navlist.item icon="phone-x-mark" href="{{ route('atendentes') }}" wire:navigate>Atendentes</flux:navlist.item>
        <flux:navlist.item icon="clipboard-document-list" href="{{ route('ordem-servico') }}" wire:navigate>Ordem de Serviço</flux:navlist.item>
        <flux:navlist.group expandable heading="Contratos" class="grid">
            <flux:navlist.item icon="document" href="{{ route('contratos') }}" wire:navigate>Todos os Contratos</flux:navlist.item>
            <flux:navlist.item icon="calendar" href="{{ route('contratos.mes') }}" wire:navigate>Contratos do mês</flux:navlist.item>
        </flux:navlist.group>
        {{--        <flux:navlist.item icon="user-group" href="{{ route('usuarios') }}" wire:navigate>Usuários</flux:navlist.item>--}}
        <flux:navlist.item icon="chart-bar-square" href="{{ route('categoria-entrada-saida') }}" wire:navigate>Cat. Entrada e Saída</flux:navlist.item>
        <flux:navlist.item icon="arrows-up-down" href="{{ route('entradas-saidas') }}" wire:navigate>Entrada e Saída</flux:navlist.item>
        <flux:navlist.item icon="banknotes" href="{{ route('bancos') }}" wire:navigate>Bancos</flux:navlist.item>
    </flux:navlist>
    <flux:spacer/>


    <livewire:layout.navigation/>
</flux:sidebar>

<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

    <flux:spacer/>

    <flux:dropdown position="top" alignt="start">
        <flux:profile/>

        <flux:menu>
            <flux:menu.radio.group>
                <flux:menu.radio>{{ auth()->user()->name }}</flux:menu.radio>
            </flux:menu.radio.group>

            <flux:menu.separator/>

            <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:header>

<flux:main>
    {{ $slot }}
</flux:main>

@persist('toast')
<flux:toast/>
@endpersist

@fluxScripts

</body>
</html>
