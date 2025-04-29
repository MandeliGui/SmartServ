<?php

use App\Livewire\Forms\Tecnicoform;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public Tecnicoform $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="xl">Tecnicos</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.tecnico.search/>
    </flux:card>



    <flux:modal name="{{\App\Livewire\Forms\TecnicoForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.tecnico.remove/>
    </flux:modal>

</div>
