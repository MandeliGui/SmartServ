<?php

use App\Livewire\Forms\TecnicoForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public TecnicoForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading class="text-accent" size="xl">Novo Cliente</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.tecnico.create-update/>
    </flux:card>

</div>
