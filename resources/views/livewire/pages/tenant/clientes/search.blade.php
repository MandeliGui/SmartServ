<?php

use App\Livewire\Forms\ClientesForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public ClientesForm $form;

}; ?>

<div>


    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="lg">Clientes</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.clientes.search/>
    </flux:card>

    <flux:modal name="{{\App\Livewire\Forms\ClientesForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.clientes.remove/>
    </flux:modal>

</div>
