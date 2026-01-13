<?php

use App\Enums\Persistence;
use App\Enums\Tenant\PeriodicidadeEnum;
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
    public mixed $formasPagamento       = [];
    public bool  $isRecorrente          = false;

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
        $this->persistence  = Persistence::CREATE;
        $this->isRecorrente = false;
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
        $this->form->categoria_id    = null;
        $this->categoriaEntradaSaida = \App\Models\CategoriaEntradaSaidaModel::query()
                                                                             ->where('tipo', $this->form->tipo)
                                                                             ->where('removido', false)
                                                                             ->get();
    }

    public function updatedIsRecorrente(): void
    {
        if (!$this->isRecorrente) {
            $this->form->quantidade_meses = 1;
            $this->form->periodicidade    = null;
        }
    }

    public function updatedFormSituacao(): void
    {

        if (!$this->form->situacao) {
            $this->form->data_pagamento = null;
            $this->form->valor_pago     = null;
        }
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


            <flux:date-picker wire:model="form.data_vencimento">

                <x-slot name="trigger">
                    <flux:date-picker.input label="Data de Vencimento*"

                                            name="data_vencimento"

                    />
                </x-slot>

            </flux:date-picker>

            <flux:input label="Valor*" type="text" wire:model="form.valor_original" name="valor_original"
                        placeholder="R$ 0,00" :disabled="$this->persistence == \App\Enums\Persistence::UPDATE" is-decimal="true"/>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 my-4">

            <div class="">
                <flux:field variant="inline">
                    <flux:checkbox wire:model.live="isRecorrente"/>
                    <flux:label>Esse movimento se repete?</flux:label>
                    <flux:error name="terms"/>
                </flux:field>
            </div>
        </div>

        @if($this->isRecorrente)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">

                <flux:input label="Quantidade de meses*" type="text" wire:model="form.quantidade_meses"
                            name="quantidade_meses"/>

                <flux:select variant="listbox" placeholder="Informe o periodo" label="Periodicidade*"
                             wire:model="form.periodicidade"
                             name="periodicidade">

                    <flux:select.option value="{{PeriodicidadeEnum::MENSAL->value}}">{{PeriodicidadeEnum::MENSAL->value}}</flux:select.option>
                    <flux:select.option value="{{PeriodicidadeEnum::BIMESTRAL->value}}">{{PeriodicidadeEnum::BIMESTRAL->value}}</flux:select.option>
                    <flux:select.option value="{{PeriodicidadeEnum::TRIMESTRAL->value}}">{{PeriodicidadeEnum::TRIMESTRAL->value}}</flux:select.option>
                    <flux:select.option value="{{PeriodicidadeEnum::SEMESTRAL->value}}">{{PeriodicidadeEnum::SEMESTRAL->value}}</flux:select.option>
                    <flux:select.option value="{{PeriodicidadeEnum::ANUAL->value}}">{{PeriodicidadeEnum::ANUAL->value}}</flux:select.option>

                </flux:select>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">

            <flux:field>
                <div class="flex items-center space-x-2 mb-4">
                    <flux:label class="leading-none">Situação*</flux:label>
                    @if($isRecorrente)
                        <flux:tooltip content="Movimentos recorrentes: após criar os lançamentos, registre o pagamento de cada parcela individualmente.">
                            <flux:icon.information-circle class="text-amber-500 h-5 w-5"/>
                        </flux:tooltip>
                    @endif
                </div>
                <flux:select variant="listbox" placeholder="Informe a situação"
                             wire:model.live="form.situacao"
                             name="situacao" :disabled="$isRecorrente">

                    <flux:select.option value="0">Pendente</flux:select.option>
                    <flux:select.option value="1">Pago</flux:select.option>

                </flux:select>
            </flux:field>


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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
            @if($form->situacao)

                <flux:date-picker wire:model="form.data_pagamento">

                    <x-slot name="trigger">
                        <flux:date-picker.input label="Data de pagamento*"

                                                name="data_pagamento"

                        />
                    </x-slot>

                </flux:date-picker>

                <flux:input label="Valor pago*" type="text" wire:model="form.valor_pago" name="valor_pago"
                            placeholder="R$ 0,00" :disabled="$this->persistence == \App\Enums\Persistence::UPDATE" is-decimal="true"/>

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
            @endif


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

