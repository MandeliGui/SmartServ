<?php

use App\Enums\Persistence;

use App\Livewire\Forms\ContratoForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public ContratoForm $form;

    public Persistence $persistence;


    public function remove(): void
    {
        $this->form->remove();

        $this->dispatch(ContratoForm::EVENT_PERSISTED, persistence: Persistence::REMOVE->value);

        Flux::modal(ContratoForm::MODAL_NAME_REMOVE)->close();
        Flux::toast('Contrato removido com sucesso!', variant: 'success');
    }

    #[On(ContratoForm::EVENT_NAME_SHOW_MODAL_REMOVE)]
    public function openModalRemove(string $modalName, int $id): void
    {


        $this->form->setContrato($id);

        $this->persistence = Persistence::REMOVE;

        Flux::modal($modalName)->show();
    }


};

?>

<div>
    <form wire:submit.prevent="remove">

        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Remover contrato?</flux:heading>
                <flux:text class="mt-2">
                    <p>Tem certeza que deseja remover o contrato?</p>


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
