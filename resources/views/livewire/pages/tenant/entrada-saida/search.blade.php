<?php

use App\Livewire\Forms\EntradasSaidasForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public EntradasSaidasForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="xl">Entradas e Saidas</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.entrada-saida.search/>
    </flux:card>

    <flux:modal name="{{\App\Livewire\Forms\EntradasSaidasForm::MODAL_NAME_CREATE}}" class="min-w-[88rem]">
        <livewire:forms.entrada-saida.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\EntradasSaidasForm::MODAL_NAME_UPDATE}}" class="min-w-[88rem]">
        <livewire:forms.entrada-saida.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\EntradasSaidasForm::MODAL_NAME_DAR_BAIXA}}" class="min-w-[88rem]">
        <livewire:forms.entrada-saida.dar-baixa/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\EntradasSaidasForm::MODAL_NAME_REMOVE}}" class="min-w-[88rem]">
        <livewire:forms.entrada-saida.remove/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\CategoriasEntradaSaidaForm::MODAL_NAME_CREATE}}">
        <livewire:forms.categoria-entrada-saida.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\BancosForm::MODAL_NAME_CREATE}}">
        <livewire:forms.bancos.create-update/>
    </flux:modal>

</div>
