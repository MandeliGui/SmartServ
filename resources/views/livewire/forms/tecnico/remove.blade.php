<?php

use App\Enums\Persistence;

use App\Livewire\Forms\TecnicoForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public TecnicoForm $form;

    public Persistence $persistence;


    public function remove(): void
    {
        $this->form->remove();

        $this->dispatch(TecnicoForm::EVENT_PERSISTED, persistence: Persistence::REMOVE->value);

        Flux::modal(TecnicoForm::MODAL_NAME_REMOVE)->close();
        Flux::toast('Tecnico removido com sucesso!', variant: 'success');
    }

    #[On(TecnicoForm::EVENT_NAME_SHOW_MODAL_REMOVE)]
    public function openModalRemove(string $modalName, int $id): void
    {


        $this->form->reset();

        $this->form->setTecnico($id);

        $this->persistence = Persistence::REMOVE;

        Flux::modal($modalName)->show();
    }


};

?>

<div>
    <form wire:submit.prevent="remove">

        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Remover tecnico?</flux:heading>
                <flux:text class="mt-2">
                    <p>Tem certeza que deseja remover o tecnico:</p>
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
