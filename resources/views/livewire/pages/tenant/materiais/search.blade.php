<?php

use App\Livewire\Forms\MateriaisForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public MateriaisForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="xl">Materiais</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.materiais.search/>
    </flux:card>

    <flux:modal name="{{\App\Livewire\Forms\MateriaisForm::MODAL_NAME_CREATE}}" class="min-w-[22rem]">
        <livewire:forms.materiais.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\MateriaisForm::MODAL_NAME_UPDATE}}" class="min-w-[22rem]">
        <livewire:forms.materiais.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\MateriaisForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.materiais.remove/>
    </flux:modal>

</div>
