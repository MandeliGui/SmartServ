<?php

use App\Livewire\Forms\ServicosForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public ServicosForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="lg">Servicos</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.servicos.search/>
    </flux:card>

    <flux:modal name="{{\App\Livewire\Forms\ServicosForm::MODAL_NAME_CREATE}}" class="min-w-[22rem]">
        <livewire:forms.servicos.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\ServicosForm::MODAL_NAME_UPDATE}}" class="min-w-[22rem]">
        <livewire:forms.servicos.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\ServicosForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.servicos.remove/>
    </flux:modal>

</div>
