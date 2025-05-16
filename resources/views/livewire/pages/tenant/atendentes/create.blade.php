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
            <flux:heading class="text-accent" size="xl">Novo Atendente</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.atendentes.create-update/>
    </flux:card>

</div>
