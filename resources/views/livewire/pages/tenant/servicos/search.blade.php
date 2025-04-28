<?php

use App\Livewire\Forms\ServicosForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public ServicosForm $form;

}; ?>

<div>

    <x-slot name="header">

        <h2 class="font-smartserv-font-title font-medium text-2xl text-smartserv-color-primary-1000 dark:text-smartserv-color-primary-dark-1000 leading-tight">
            {{ __('Serviços') }}
        </h2>

    </x-slot>

    <x-card>

        <livewire:forms.servicos.search/>

        @teleport('body')

        <div>

            <x-modal
                :name="ServicosForm::MODAL_NAME_CREATE"
                :show="$errors->isNotEmpty()"
                :modal_title="__('Novo serviço')"
                focusable
                :maxWidth="'3xl'"
            >

                <livewire:forms.servicos.create-update/>

            </x-modal>

            <x-modal
                :name="ServicosForm::MODAL_NAME_UPDATE"
                :show="$errors->isNotEmpty()"
                :modal_title="__('Alterar cliente')"
                focusable
                :maxWidth="'3xl'"
            >

                <livewire:forms.servicos.create-update/>

            </x-modal>

            <x-modal
                :name="ServicosForm::MODAL_NAME_REMOVE"
                :show="$errors->isNotEmpty()"
                :maxWidth="'sm'"
            >

                <livewire:forms.servicos.remove/>

            </x-modal>


        </div>

        @endteleport

    </x-card>

    <div class="pt-6"></div>

</div>
