<?php

use App\Livewire\Forms\GrupoClientesForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public GrupoClientesForm $form;

}; ?>

<div>

    <x-slot name="header">

        <h2 class="font-smartserv-font-title font-medium text-2xl text-smartserv-color-primary-1000 dark:text-smartserv-color-primary-dark-1000 leading-tight">
            {{ __('Grupo Clientes') }}
        </h2>

    </x-slot>

    <x-card>

        <livewire:forms.grupo-cliente.search/>

        @teleport('body')

        <div>

            <x-modal
                :name="GrupoClientesForm::MODAL_NAME_CREATE"
                :show="$errors->isNotEmpty()"
                :modal_title="__('Novo Grupo Cliente')"
                focusable
                :maxWidth="'3xl'"
            >

                <livewire:forms.grupo-cliente.create-update/>

            </x-modal>

            <x-modal
                :name="GrupoClientesForm::MODAL_NAME_UPDATE"
                :show="$errors->isNotEmpty()"
                :modal_title="__('Alterar Grupo Cliente')"
                focusable
                :maxWidth="'3xl'"
            >

                <livewire:forms.grupo-cliente.create-update/>

            </x-modal>

            <x-modal
                :name="GrupoClientesForm::MODAL_NAME_REMOVE"
                :show="$errors->isNotEmpty()"
                :maxWidth="'sm'"
            >

                <livewire:forms.grupo-cliente.remove/>

            </x-modal>


        </div>

        @endteleport

    </x-card>

    <div class="pt-6"></div>

</div>
