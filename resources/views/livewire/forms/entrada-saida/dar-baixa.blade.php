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
    public ?Persistence       $persistence = Persistence::UPDATE;

    public mixed $categoriaEntradaSaida = [];
    public mixed $bancos                = [];
    public mixed $formasPagamento       = [];


    public function save(): void
    {

        $data         = \App\Http\Requests\Tenant\EntradaSaidaRequest::darBaixa($this->form->all())->validated();
        $entradaSaida = $this->form->darBaixa();
        $this->dispatch(EntradasSaidasForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, entradaSaida: $entradaSaida);


        Flux::modal(EntradasSaidasForm::MODAL_NAME_DAR_BAIXA)->close();


        Flux::toast('Baixa realizada com sucesso!', variant: 'success');


    }

    public function updatedFormValor($value)
    {
        $this->form->valor = str_replace(['.', ','], ['', '.'], $value);
    }

    #[On(EntradasSaidasForm::EVENT_NAME_SHOW_MODAL_DAR_BAIXA)]
    public function openModalDarBaixa(string $modalName, $id)
    {
        $this->form->setEntradaSaida($id);

        $this->form->data_pagamento  = \Carbon\Carbon::now()->format('Y-m-d');
        $this->categoriaEntradaSaida = \App\Models\CategoriaEntradaSaidaModel::query()
            ->where('id', $this->form->categoria_id)
            ->where('tipo', $this->form->tipo)
            ->get();


        Flux::modal($modalName)->show();
    }


    public function mount()
    {
        $this->bancos          = \App\Models\BancosModel::query()->get();
        $this->formasPagamento = \App\Models\FormaPagamentoModel::query()->get();
    }

};
?>

<div x-data>


    <form wire:submit.prevent="save">


        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-4">

            <flux:select variant="listbox" placeholder="Selecione o tipo" label="Tipo*" wire:model="form.tipo"
                         name="tipo" :disabled="$this->persistence == \App\Enums\Persistence::UPDATE"
                         wire:change="alterarCategoriaEntradaSaida">
                <flux:select.option
                    value="{{ \App\Enums\TipoEntradaSaida::ENTRADA->value }}"
                    :selected="isset($this->form->tipo) && $this->form->tipo == \App\Enums\TipoEntradaSaida::ENTRADA->value"
                >
                    Entrada
                </flux:select.option>
                <flux:select.option
                    value="{{ \App\Enums\TipoEntradaSaida::SAIDA->value }}"
                    :selected="isset($this->form->tipo) && $this->form->tipo == \App\Enums\TipoEntradaSaida::SAIDA->value"
                >
                    Saída
                </flux:select.option>
            </flux:select>

            <flux:select variant="listbox" placeholder="Selecione a categoria" label="Categoria*"
                         :disabled="$this->persistence == \App\Enums\Persistence::UPDATE"
                         wire:model="form.categoria_id"
                         name="categoria_id">


                @foreach($categoriaEntradaSaida as $categoria)
                    <flux:select.option value="" selected>{{ $categoria->nome }}</flux:select.option>
                @endforeach


            </flux:select>


            <flux:date-picker wire:model="date" :disabled="$this->persistence == \App\Enums\Persistence::UPDATE">

                <x-slot name="trigger">
                    <flux:date-picker.input label="Data de Vencimento*"
                                            wire:model="form.data_vencimento"


                    />
                </x-slot>

            </flux:date-picker>
            <flux:input label="Valor*" type="text"
                        wire:model="form.valor_original"  :disabled="$this->persistence == \App\Enums\Persistence::UPDATE"/>

            <flux:select variant="listbox" placeholder="Selecione a forma de pagamento" label="Forma Pagamento*"
                         wire:model="form.forma_pagamento_id"
                         name="forma_pagamento_id">
                @if(count($formasPagamento) > 0)

                    @foreach($formasPagamento as $forma)
                        <flux:select.option value="{{ $forma->id }}">{{ $forma->nome }}</flux:select.option>
                    @endforeach
                @else
                    <flux:text class="m-2">Nenhuma forma de pagamento disponível</flux:text>
                @endif


            </flux:select>

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


            <flux:date-picker wire:model="date">

                <x-slot name="trigger">
                    <flux:date-picker.input label="Data de pagamento*"
                                            wire:model="form.data_pagamento"
                                            name="data_pagamento"


                    />
                </x-slot>

            </flux:date-picker>


            <flux:input label="Valor Pago*" type="text" wire:model="form.valor_pago" name="valor_pago"
                        placeholder="R$ 0,00"/>


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

