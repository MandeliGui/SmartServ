<?php

use App\Livewire\Forms\AtendenteForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public AtendenteForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="xl">Atendentes</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.atendentes.search/>
    </flux:card>


    <flux:modal name="{{AtendenteForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.atendentes.remove/>
    </flux:modal>

</div>
