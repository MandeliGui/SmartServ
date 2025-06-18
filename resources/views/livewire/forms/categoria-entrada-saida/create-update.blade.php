<?php

use App\Enums\Persistence;
use App\Livewire\Forms\CategoriasEntradaSaidaForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public CategoriasEntradaSaidaForm $form;
    public ?Persistence               $persistence = Persistence::CREATE;

    public function save(): void
    {
        $this->persistence = !empty($this->form->id) ? Persistence::UPDATE : Persistence::CREATE;

        if ($this->persistence == Persistence::UPDATE) {

            $categoria = $this->form->update();
            $this->dispatch(CategoriasEntradaSaidaForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, categoria: $categoria);


            Flux::modal(CategoriasEntradaSaidaForm::MODAL_NAME_UPDATE)->close();


            Flux::toast('Forma Pagamento editada com sucesso!', variant: 'success');


        } else {

            $categoria = $this->form->create();


            $this->dispatch(CategoriasEntradaSaidaForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, categoria: $categoria);
            $this->form->reset();
            Flux::modal(CategoriasEntradaSaidaForm::MODAL_NAME_CREATE)->close();
            Flux::toast('Forma Pagamento criada com sucesso!', variant: 'success');
        }

    }

    public function updatedFormValor($value)
    {
        $this->form->valor = str_replace(['.', ','], ['', '.'], $value);
    }

    #[On(CategoriasEntradaSaidaForm::EVENT_NAME_SHOW_MODAL_CREATE)]
    public function openModalCreate(string $modalName)
    {
        $this->persistence = Persistence::CREATE;
        $this->form->reset();
        Flux::modal($modalName)->show();
    }

    #[On(CategoriasEntradaSaidaForm::EVENT_NAME_SHOW_MODAL_UPDATE)]
    public function openModalUpdate(string $modalName, int $id)
    {
        $this->persistence = Persistence::UPDATE;
        $this->form->setCategoriaEntradaSaida($id);
        Flux::modal($modalName)->show();

    }

};
?>

<div x-data>
    <form wire:submit.prevent="save">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">

            <flux:select variant="listbox" placeholder="Selecione o tipo" label="Tipo*" wire:model="form.tipo"
                         name="tipo" :disabled="$this->persistence == \App\Enums\Persistence::UPDATE">
                <flux:select.option value="{{\App\Enums\TipoEntradaSaida::ENTRADA->value}}">Entrada</flux:select.option>
                <flux:select.option value="{{\App\Enums\TipoEntradaSaida::SAIDA->value}}">Saída</flux:select.option>

            </flux:select>
            <flux:input label="Nome*" placeholder="Digite o nome"
                        wire:model="form.nome"
                        name="nome"
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

