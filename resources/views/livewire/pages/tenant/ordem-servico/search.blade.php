<?php

use App\Livewire\Forms\OrdemServicoForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public OrdemServicoForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="xl">Ordens de Servico</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.ordem-servico.search/>
    </flux:card>


    <flux:modal name="{{OrdemServicoForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.ordem-servico.remove/>
    </flux:modal>

</div>
