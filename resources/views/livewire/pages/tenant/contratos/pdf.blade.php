<?php

use App\Livewire\Forms\OrdemServicoForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')]
class extends Component {

    public OrdemServicoForm $form;



}; ?>


<div>
    <livewire:forms.ordem-servico.pdf :ordem-servico="$ordemServico" />
</div>
