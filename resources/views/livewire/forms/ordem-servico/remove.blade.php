<?php

use App\Enums\Persistence;

use App\Livewire\Forms\OrdemServicoForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public OrdemServicoForm $form;

    public Persistence $persistence;


    public function remove(): void
    {
        $this->form->remove();

        $this->dispatch(OrdemServicoForm::EVENT_PERSISTED, persistence: Persistence::REMOVE->value);

        Flux::modal(OrdemServicoForm::MODAL_NAME_REMOVE)->close();
        Flux::toast('Ordem de Serviço removido com sucesso!', variant: 'success');
    }

    #[On(OrdemServicoForm::EVENT_NAME_SHOW_MODAL_REMOVE)]
    public function openModalRemove(string $modalName, int $id): void
    {


        $this->form->reset();

        $this->form->setOrdemServico($id);

        $this->persistence = Persistence::REMOVE;

        Flux::modal($modalName)->show();
    }


};

?>

<div>
    <form wire:submit.prevent="remove">

        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Remover atendente?</flux:heading>
                <flux:text class="mt-2">
                    <p>Tem certeza que deseja remover a O.S:</p>
                    <strong>Codigo: {{$this->form->codigo ?? null}} </strong>
                    <br>
                    <strong>Cliente: {{$this->form->nomeCliente ?? null}}</strong>

                    ?

                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger">Remover</flux:button>
            </div>
        </div>
    </form>
</div>
