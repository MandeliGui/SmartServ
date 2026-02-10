<?php

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
            <flux:heading class="text-accent" size="xl">Contrato</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.contratos.create-update/>
    </flux:card>

    <flux:modal name="{{\App\Livewire\Forms\AdicionarMateriaisForm::MODAL_NAME_SELECIONAR_MATERIAL}}"
                class="min-w-[88rem]">
        <livewire:forms.contratos.selecionar-material/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\AdicionarServicosForm::MODAL_NAME_SELECIONAR_SERVICO}}" class="min-w-[88rem]">
        <livewire:forms.contratos.selecionar-servico/>
    </flux:modal>

</div>
