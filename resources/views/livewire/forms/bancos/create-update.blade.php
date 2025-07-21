<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\BancosForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public BancosForm   $form;
    public ?Persistence $persistence = Persistence::CREATE;

    public function save(): void
    {
        $this->persistence = !empty($this->form->id) ? Persistence::UPDATE : Persistence::CREATE;

        if ($this->persistence == Persistence::UPDATE) {

            $servico = $this->form->update();
            $this->dispatch(BancosForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, servico: $servico);


            Flux::modal(BancosForm::MODAL_NAME_UPDATE)->close();


            Flux::toast('Forma Pagamento editada com sucesso!', variant: 'success');


        } else {

            $servico = $this->form->create();


            $this->dispatch(BancosForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, servico: $servico);
            $this->form->reset();
            Flux::modal(BancosForm::MODAL_NAME_CREATE)->close();
            Flux::toast('Forma Pagamento criada com sucesso!', variant: 'success');
        }

    }

    public function updatedFormValor($value)
    {
        $this->form->valor = str_replace(['.', ','], ['', '.'], $value);
    }

    #[On(BancosForm::EVENT_NAME_SHOW_MODAL_CREATE)]
    public function openModalCreate(string $modalName)
    {
        $this->persistence = Persistence::CREATE;
        $this->form->reset();
        Flux::modal($modalName)->show();
    }

    #[On(BancosForm::EVENT_NAME_SHOW_MODAL_UPDATE)]
    public function openModalUpdate(string $modalName, int $id)
    {
        $this->persistence = Persistence::UPDATE;
        $this->form->setBanco($id);
        Flux::modal($modalName)->show();


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

        @if($this->persistence == Persistence::CREATE)
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 my-4">
                <flux:input
                    label="Saldo Inicial"
                    wire:model="form.saldo_inicial"
                    name="saldo_inicial"
                />

            </div>
        @endif


        <flux:button type="submit" variant="primary" class="mt-2">Salvar</flux:button>

    </form>
</div>

