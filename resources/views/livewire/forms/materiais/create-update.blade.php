<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\MateriaisForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public MateriaisForm $form;
    public ?Persistence  $persistence = Persistence::CREATE;

    public function save(): void
    {
        $this->persistence = !empty($this->form->id) ? Persistence::UPDATE : Persistence::CREATE;

        if ($this->persistence == Persistence::UPDATE) {

            $material = $this->form->update();
            $this->dispatch(MateriaisForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, material: $material);


            Flux::modal(MateriaisForm::MODAL_NAME_UPDATE)->close();


            Flux::toast('Serviço editado com sucesso!', variant: 'success');


        } else {

            $material = $this->form->create();


            $this->dispatch(MateriaisForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, material: $material);
            $this->form->reset();
            Flux::modal(MateriaisForm::MODAL_NAME_CREATE)->close();
            Flux::toast('Serviço criado com sucesso!', variant: 'success');
        }

    }

    public function updatedFormValor($value)
    {
        $this->form->valor = str_replace(['.', ','], ['', '.'], $value);
    }

    #[On(MateriaisForm::EVENT_NAME_SHOW_MODAL_CREATE)]
    public function openModalCreate(string $modalName)
    {

        $this->form->reset();
        Flux::modal($modalName)->show();
    }

    #[On(MateriaisForm::EVENT_NAME_SHOW_MODAL_UPDATE)]
    public function openModalUpdate(string $modalName, int $id)
    {
        $this->form->setMaterial($id);
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
            <flux:select label="Unidade*" placeholder="Selecione a unidade"
                         wire:model="form.unidade"
                         name="unidade"
            >
                <flux:select.option value="UN">Unidade</flux:select.option>
                <flux:select.option value="KG">Quilo</flux:select.option>
                <flux:select.option value="L">Litro</flux:select.option>
                <flux:select.option value="M">Metro</flux:select.option>
                <flux:select.option value="CM">Centimetro</flux:select.option>

            </flux:select>


            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 my-4">
                <flux:textarea
                    label="Descricao"
                    wire:model="form.descricao"
                    name="descricao"
                />

            </div>


        </div>

        <flux:button type="submit" variant="primary" class="mt-2">Salvar</flux:button>

    </form>
</div>

