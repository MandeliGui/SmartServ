<?php

use App\Livewire\Forms\ClientesForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')]
class extends Component {

    public ClientesForm $form;

}; ?>

<div>


    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="xl">Fornecedores</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.fornecedores.search/>
    </flux:card>

    <flux:modal name="{{\App\Livewire\Forms\FornecedoresForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.fornecedores.remove/>
    </flux:modal>

</div>
