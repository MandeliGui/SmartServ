<?php

use App\Livewire\Forms\FormaPagamentoForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public FormaPagamentoForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading size="xl">Formas Pagamento</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.forma-pagamento.search/>
    </flux:card>

    <flux:modal name="{{\App\Livewire\Forms\FormaPagamentoForm::MODAL_NAME_CREATE}}" class="min-w-[22rem]">
        <livewire:forms.forma-pagamento.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\FormaPagamentoForm::MODAL_NAME_UPDATE}}" class="min-w-[22rem]">
        <livewire:forms.forma-pagamento.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\FormaPagamentoForm::MODAL_NAME_REMOVE}}" class="min-w-[22rem]">
        <livewire:forms.forma-pagamento.remove/>
    </flux:modal>

</div>
