<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\FormaPagamentoForm;
use App\Models\ServicosModel;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public FormaPagamentoForm $form;
    public ?Persistence       $persistence = Persistence::CREATE;

    public function save(): void
    {
        $this->persistence = !empty($this->form->id) ? Persistence::UPDATE : Persistence::CREATE;

        if ($this->persistence == Persistence::UPDATE) {

            $servico = $this->form->update();
            $this->dispatch(FormaPagamentoForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, servico: $servico);


            Flux::modal(FormaPagamentoForm::MODAL_NAME_UPDATE)->close();


            Flux::toast('Forma Pagamento editada com sucesso!', variant: 'success');


        } else {

            $servico = $this->form->create();


            $this->dispatch(FormaPagamentoForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, servico: $servico);
            $this->form->reset();
            Flux::modal(FormaPagamentoForm::MODAL_NAME_CREATE)->close();
            Flux::toast('Forma Pagamento criada com sucesso!', variant: 'success');
        }

    }

    public function updatedFormValor($value)
    {
        $this->form->valor = str_replace(['.', ','], ['', '.'], $value);
    }

    #[On(FormaPagamentoForm::EVENT_NAME_SHOW_MODAL_CREATE)]
    public function openModalCreate(string $modalName)
    {
        $this->form->reset();
        Flux::modal($modalName)->show();
    }

    #[On(FormaPagamentoForm::EVENT_NAME_SHOW_MODAL_UPDATE)]
    public function openModalUpdate(string $modalName, int $id)
    {
        $this->form->setFormaPagamento($id);
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

