<?php


use App\Livewire\Forms\GrupoClientesForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public GrupoClientesForm $form;

}; ?>

<div>

    <x-card>

        <livewire:forms.grupo-cliente.create-update/>


    </x-card>

    <div class="pt-6"></div>

</div>
