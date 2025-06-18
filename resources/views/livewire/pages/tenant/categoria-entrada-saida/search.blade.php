<?php

use App\Livewire\Forms\CategoriasEntradaSaidaForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public CategoriasEntradaSaidaForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="xl">Categorias Entrada e Saida</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.categoria-entrada-saida.search/>
    </flux:card>

    <flux:modal name="{{\App\Livewire\Forms\CategoriasEntradaSaidaForm::MODAL_NAME_CREATE}}" class="min-w-[22rem]">
        <livewire:forms.categoria-entrada-saida.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\CategoriasEntradaSaidaForm::MODAL_NAME_UPDATE}}" class="min-w-[22rem]">
        <livewire:forms.categoria-entrada-saida.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\CategoriasEntradaSaidaForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.categoria-entrada-saida.remove/>
    </flux:modal>

</div>
