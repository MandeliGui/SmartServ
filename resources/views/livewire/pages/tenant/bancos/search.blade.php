<?php

use App\Livewire\Forms\BancosForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public BancosForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="xl">Bancos</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.bancos.search/>
    </flux:card>

    <flux:modal name="{{\App\Livewire\Forms\BancosForm::MODAL_NAME_CREATE}}" class="min-w-[22rem]">
        <livewire:forms.bancos.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\BancosForm::MODAL_NAME_UPDATE}}" class="min-w-[22rem]">
        <livewire:forms.bancos.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\BancosForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.bancos.remove/>
    </flux:modal>

</div>
