<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\GrupoClientesForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public GrupoClientesForm $form;
    public ?Persistence      $persistence = Persistence::CREATE;

    public function save(): void
    {
        $this->persistence = !empty($this->form->id) ? Persistence::UPDATE : Persistence::CREATE;

        if ($this->persistence == Persistence::UPDATE) {

            $servico = $this->form->update();
            $this->dispatch(GrupoClientesForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, servico: $servico);


            Flux::modal(GrupoClientesForm::MODAL_NAME_UPDATE)->close();


            Flux::toast('Grupo de clientes editado com sucesso!', variant: 'success');


        } else {

            $servico = $this->form->create();


            $this->dispatch(GrupoClientesForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, servico: $servico);
            $this->form->reset();
            Flux::modal(GrupoClientesForm::MODAL_NAME_CREATE)->close();
            Flux::toast('Grupo de clientes criado com sucesso!', variant: 'success');
        }

    }

    #[On(GrupoClientesForm::EVENT_NAME_SHOW_MODAL_CREATE)]
    public function openModalCreate(string $modalName)
    {
        $this->form->reset();
        Flux::modal($modalName)->show();
    }

    #[On(GrupoClientesForm::EVENT_NAME_SHOW_MODAL_UPDATE)]
    public function openModalUpdate(string $modalName, int $id)
    {
        $this->form->setGrupo($id);
        Flux::modal($modalName)->show();

    }



//    public function with()
//    {
//
//    }

    public function mount()
    {

    }


};
?>

<div x-data>
    <form wire:submit.prevent="save">

        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 my-4">
            <flux:input label="Nome*" placeholder="Digite o nome"
                        wire:model="form.nome"
                        name="nome"
            />
        </div>


        <flux:button type="submit" variant="primary" class="mt-2">Salvar</flux:button>

    </form>
</div>

