<?php

use App\Enums\Persistence;
use App\Livewire\Forms\EntradasSaidasForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public EntradasSaidasForm $form;
    public ?Persistence       $persistence = Persistence::CREATE;

    public mixed $categoriaEntradaSaida = [];
    public mixed $bancos                = [];

    public function save(): void
    {
        $this->persistence = !empty($this->form->id) ? Persistence::UPDATE : Persistence::CREATE;

        if ($this->persistence == Persistence::UPDATE) {

            $servico = $this->form->update();
            $this->dispatch(EntradasSaidasForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, servico: $servico);


            Flux::modal(EntradasSaidasForm::MODAL_NAME_UPDATE)->close();


            Flux::toast('Forma Pagamento editada com sucesso!', variant: 'success');


        } else {

            $servico = $this->form->create();


            $this->dispatch(EntradasSaidasForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, servico: $servico);
            $this->form->reset();
            Flux::modal(EntradasSaidasForm::MODAL_NAME_CREATE)->close();
            Flux::toast('Forma Pagamento criada com sucesso!', variant: 'success');
        }

    }

    public function updatedFormValor($value)
    {
        $this->form->valor = str_replace(['.', ','], ['', '.'], $value);
    }

    #[On(EntradasSaidasForm::EVENT_NAME_SHOW_MODAL_CREATE)]
    public function openModalCreate(string $modalName)
    {
        $this->persistence = Persistence::CREATE;
        $this->form->reset();
        Flux::modal($modalName)->show();
    }

    #[On(EntradasSaidasForm::EVENT_NAME_SHOW_MODAL_UPDATE)]
    public function openModalUpdate(string $modalName, int $id)
    {
        $this->persistence = Persistence::UPDATE;
        $this->form->setEntradaSaida($id);
        Flux::modal($modalName)->show();

    }

    public function alterarCategoriaEntradaSaida()
    {
        $this->form->categoria_id = null;
        $this->categoriaEntradaSaida = \App\Models\CategoriaEntradaSaidaModel::query()->where('tipo', $this->form->tipo)
            ->where('removido', false)
            ->get();
    }

    public function mount()
    {
        $this->bancos = \App\Models\BancosModel::query()->get();
    }

};
?>

<div x-data>
    <form wire:submit.prevent="save">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">

            <flux:select variant="listbox" placeholder="Selecione o tipo" label="Tipo*" wire:model="form.tipo"
                         name="tipo" :disabled="$this->persistence == \App\Enums\Persistence::UPDATE"
                         wire:change="alterarCategoriaEntradaSaida">
                <flux:select.option value="{{\App\Enums\TipoEntradaSaida::ENTRADA->value}}">Entrada</flux:select.option>
                <flux:select.option value="{{\App\Enums\TipoEntradaSaida::SAIDA->value}}">Saída</flux:select.option>

            </flux:select>

            <flux:select variant="listbox" placeholder="Selecione a categoria" label="Categoria*"
                         wire:model="form.categoria_id"
                         name="categoria_id">
                @if(count($categoriaEntradaSaida) > 0)

                    @foreach($categoriaEntradaSaida as $categoria)
                        <flux:select.option value="{{ $categoria->id }}">{{ $categoria->nome }}</flux:select.option>
                    @endforeach
                @else
                    <flux:text class="m-2">Nenhuma categoria disponível</flux:text>
                @endif

            </flux:select>


            <flux:date-picker  wire:model="form.data_vencimento">

                <x-slot name="trigger">
                    <flux:date-picker.input label="Data de Vencimento*"

                                            name="data_vencimento"

                    />
                </x-slot>

            </flux:date-picker>

            <flux:input label="Valor*" type="text" wire:model="form.valor_original" name="valor_original"
                        placeholder="R$ 0,00" :disabled="$this->persistence == \App\Enums\Persistence::UPDATE"/>

            <flux:input label="Quantidade de meses*" type="text" wire:model="form.quantidade_meses"
                        name="quantidade_meses"/>

            <flux:select variant="listbox" placeholder="Selecione o banco" label="Banco*"
                         wire:model="form.banco_id"
                         name="banco_id">
                @if(count($bancos) > 0)

                    @foreach($bancos as $banco)
                        <flux:select.option value="{{ $banco->id }}">{{ $banco->nome }}</flux:select.option>
                    @endforeach
                @else
                    <flux:text class="m-2">Nenhum banco disponível</flux:text>
                @endif

            </flux:select>


        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 my-4">
            <flux:textarea
                label="Descricao"
                wire:model="form.descricao"
                name="descricao"
            />

        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 my-4">


        </div>


        <flux:button type="submit" variant="primary" class="mt-2">Salvar</flux:button>

    </form>
</div>

