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

    <div class="pt-6"></div>

</div>
