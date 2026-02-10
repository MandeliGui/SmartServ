<?php

use App\Livewire\Forms\ContratoForm;
use App\Livewire\Forms\OrdemServicoForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')]
class extends Component {

    public OrdemServicoForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="xl">Contratos</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.contratos.search/>
    </flux:card>


    <flux:modal name="{{ContratoForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.contratos.remove/>
    </flux:modal>

</div>
