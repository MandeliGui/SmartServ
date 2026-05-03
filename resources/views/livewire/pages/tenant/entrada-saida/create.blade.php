<?php


use App\Livewire\Forms\EntradasSaidasForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public EntradasSaidasForm $form;

}; ?>

<div>

    <x-card>

        <livewire:forms.entrada-saida.create-update/>

    </x-card>

    <flux:modal name="{{\App\Livewire\Forms\CategoriasEntradaSaidaForm::MODAL_NAME_CREATE}}">
        <livewire:forms.categoria-entrada-saida.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\BancosForm::MODAL_NAME_CREATE}}">
        <livewire:forms.bancos.create-update/>
    </flux:modal>

    <div class="pt-6"></div>

</div>
