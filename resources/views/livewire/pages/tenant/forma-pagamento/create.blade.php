<?php


use App\Livewire\Forms\FormaPagamentoForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public FormaPagamentoForm $form;

}; ?>

<div>

    <x-card>

        <livewire:forms.forma-pagamento.create-update/>


    </x-card>

    <div class="pt-6"></div>

</div>
