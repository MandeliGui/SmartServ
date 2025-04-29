<?php

use App\Enums\Persistence;

use App\Livewire\Forms\ClientesForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public ClientesForm $form;

    public Persistence $persistence;


    public function remove(): void
    {

        $this->form->remove();

        $this->dispatch(ClientesForm::EVENT_PERSISTED, persistence: Persistence::REMOVE->value);

        Flux::toast('Cliente removido com sucesso!', variant: 'success');

        Flux::modal(ClientesForm::MODAL_NAME_REMOVE)->close();
    }

    #[On(ClientesForm::EVENT_NAME_SHOW_MODAL_REMOVE)]
    public function openModalRemove(string $modalName, int $id): void
    {

        $this->form->setCliente($id);
        Flux::modal($modalName)->show();
    }


};

?>
<div>

    <form wire:submit.prevent="remove">


        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Remover cliente?</flux:heading>
                <flux:text class="mt-2">
                    <p>Tem certeza que deseja remover o cliente:</p>
                    <strong>{{ $this->form->cliente->nomeFantasia ?? $this->form->nomeRazaoSocial }}</strong> ?

                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger">Delete project</flux:button>
            </div>
        </div>

    </form>
</div>
