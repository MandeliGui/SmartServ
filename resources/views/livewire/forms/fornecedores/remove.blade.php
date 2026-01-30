<?php

use App\Enums\Persistence;
use App\Livewire\Forms\FornecedoresForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public FornecedoresForm $form;

    public Persistence $persistence;


    public function remove(): void
    {

        $this->form->remove();

        $this->dispatch(FornecedoresForm::EVENT_PERSISTED, persistence: Persistence::REMOVE->value);

        Flux::toast('Fornecedor removido com sucesso!', variant: 'success');

        Flux::modal(FornecedoresForm::MODAL_NAME_REMOVE)->close();
    }

    #[On(FornecedoresForm::EVENT_NAME_SHOW_MODAL_REMOVE)]
    public function openModalRemove(string $modalName, int $id): void
    {

        $this->form->setFornecedor($id);
        Flux::modal($modalName)->show();
    }


};

?>
<div>

    <form wire:submit.prevent="remove">


        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Remover fornecedor?</flux:heading>
                <flux:text class="mt-2">
                    <p>Tem certeza que deseja remover o fornecedor:</p>
                    <strong>{{ $this->form->nomeFantasia ?? $this->form->nomeRazaoSocial }}</strong> ?

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
