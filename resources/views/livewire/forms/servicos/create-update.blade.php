<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\ServicosForm;
use App\Models\ServicosModel;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public ServicosForm $form;
    public ?Persistence $persistence = Persistence::CREATE;

    public function save(): void
    {
        $this->persistence = !empty($this->form->id) ? Persistence::UPDATE : Persistence::CREATE;

        if ($this->persistence == Persistence::UPDATE) {

            $servico = $this->form->update();
            $this->dispatch(ServicosForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, servico: $servico);


            Flux::modal(ServicosForm::MODAL_NAME_UPDATE)->close();


            Flux::toast('Serviço editado com sucesso!', variant: 'success');


        } else {

            $servico = $this->form->create();


            $this->dispatch(ServicosForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, servico: $servico);
            $this->form->reset();
            Flux::modal(ServicosForm::MODAL_NAME_CREATE)->close();
            Flux::toast('Serviço criado com sucesso!', variant: 'success');
        }

    }

    public function updatedFormValor($value)
    {
        $this->form->valor = str_replace(['.', ','], ['', '.'], $value);
    }

    #[On(ServicosForm::EVENT_NAME_SHOW_MODAL_CREATE)]
    public function openModalCreate(string $modalName)
    {
        $this->form->reset();
        Flux::modal($modalName)->show();
    }

    #[On(ServicosForm::EVENT_NAME_SHOW_MODAL_UPDATE)]
    public function openModalUpdate(string $modalName, int $id)
    {
        $this->form->setServico($id);
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
            <flux:input label="Codigo*" placeholder="Digite o codigo"
                        wire:model="form.codigo"
                        name="codigo"/>

            <flux:input label="Valor*" placeholder="Digite o valor"
                        x-mask:dynamic="$money($input, ',', '.', 2)"
                        wire:model="form.valor"
                        name="valor"
            />
        </div>


        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 my-4">
            <flux:textarea
                label="Descricao"
                wire:model="form.descricao"
                name="descricao"
            />

        </div>


        <flux:button type="submit" variant="primary" class="mt-2">Salvar</flux:button>

    </form>
</div>

