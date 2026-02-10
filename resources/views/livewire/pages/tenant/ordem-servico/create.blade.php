<?php

use App\Livewire\Forms\OrdemServicoForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')]
class extends Component {

    public OrdemServicoForm $form;

}; ?>

<div>

    <flux:card class="space-y-6">
        <div class="mb-4">
            <flux:heading class="text-accent" size="xl">Ordem de Servico</flux:heading>
            <flux:separator/>
        </div>
        <livewire:forms.ordem-servico.create-update/>
    </flux:card>

    <flux:modal name="{{\App\Livewire\Forms\AdicionarMateriaisForm::MODAL_NAME_SELECIONAR_MATERIAL}}"
                class="min-w-[88rem]">
        <livewire:forms.ordem-servico.selecionar-material/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\AdicionarServicosForm::MODAL_NAME_SELECIONAR_SERVICO}}" class="min-w-[88rem]">
        <livewire:forms.ordem-servico.selecionar-servico/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\BancosForm::MODAL_NAME_CREATE}}" class="min-w-[22rem]">
        <livewire:forms.bancos.create-update/>
    </flux:modal>

    <flux:modal name="{{\App\Livewire\Forms\FormaPagamentoForm::MODAL_NAME_CREATE}}" class="min-w-[22rem]">
        <livewire:forms.forma-pagamento.create-update/>
    </flux:modal>

</div>
