<?php

use App\Livewire\Forms\Tecnicoform;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public Tecnicoform $form;

}; ?>

<div>

    <x-slot name="header">

        <h2 class="font-smartserv-font-title font-medium text-2xl text-smartserv-color-primary-1000 dark:text-smartserv-color-primary-dark-1000 leading-tight">
            {{ __('Tecnicos') }}
        </h2>

    </x-slot>

    <x-card>

        <livewire:forms.tecnico.search/>

        @teleport('body')

        <div>

            <x-modal
                :name="\App\Livewire\Forms\TecnicoForm::MODAL_NAME_REMOVE"
                :show="$errors->isNotEmpty()"
                :maxWidth="'sm'"
            >

                <livewire:forms.tecnico.remove/>

            </x-modal>


        </div>

        @endteleport

    </x-card>

    <div class="pt-6"></div>

</div>
