<?php

use App\Livewire\Forms\UsuariosForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public UsuariosForm $form;

}; ?>

<div>

    <x-slot name="header">

        <h2 class="font-smartserv-font-title font-medium text-2xl text-smartserv-color-primary-1000 dark:text-smartserv-color-primary-dark-1000 leading-tight">
            {{ __('Usuarios') }}
        </h2>

    </x-slot>

    <x-card>

        <livewire:forms.usuarios.create-update/>


    </x-card>

    <div class="pt-6"></div>

</div>
