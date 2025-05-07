<?php

use App\Livewire\Forms\GrupoClientesForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public GrupoClientesForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="xl">Grupos Clientes</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.grupo-cliente.search/>
    </flux:card>


    <flux:modal name="{{\App\Livewire\Forms\GrupoClientesForm::MODAL_NAME_CREATE}}" class="min-w-[22rem]">
        <livewire:forms.grupo-cliente.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\GrupoClientesForm::MODAL_NAME_UPDATE}}" class="min-w-[22rem]">
        <livewire:forms.grupo-cliente.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\GrupoClientesForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.grupo-cliente.remove/>
    </flux:modal>


</div>
