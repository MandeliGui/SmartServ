<?php

use App\Enums\Persistence;
use App\Livewire\Forms\ServicosForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public ServicosForm $form;

    public Persistence $persistence;


    public function remove(): void
    {


        $this->form->remove();

        $this->dispatch(ServicosForm::EVENT_PERSISTED, persistence: Persistence::REMOVE->value);

        Flux::modal(ServicosForm::MODAL_NAME_REMOVE)->close();
        Flux::toast('Serviço removido com sucesso!', variant: 'success');

    }

    #[On(ServicosForm::EVENT_NAME_SHOW_MODAL_REMOVE)]
    public function openModalRemove(string $modalName, int $id): void
    {

        $this->form->setServico($id);
        Flux::modal($modalName)->show();
    }


};

?>

<div>
    <form wire:submit.prevent="remove">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Remover Servico?</flux:heading>
                <flux:text class="mt-2">
                    <p>Tem certeza que deseja remover o servico:</p>
                    <strong>{{$this->form->nome ?? null}} </strong> ?

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
