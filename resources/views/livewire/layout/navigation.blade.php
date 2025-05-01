<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav>

    <flux:navlist variant="outline">
        <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle"
                     aria-label="Toggle dark mode"/>
        <flux:navlist.item icon="cog-6-tooth" href="#">Configuracoes</flux:navlist.item>
        <flux:navlist.item icon="information-circle" href="#">FAQ</flux:navlist.item>
    </flux:navlist>
    <flux:dropdown position="top" align="start" class="max-lg:hidden">
        <flux:profile name="{{ auth()->user()->name }}"/>
        <flux:menu>
            <flux:menu.item icon="arrow-right-start-on-rectangle" wire:click="logout">Logout</flux:menu.item>
        </flux:menu>
    </flux:dropdown>

</nav>
