<?php

use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;

use App\Livewire\Forms\BancosForm;
use App\Livewire\Forms\EntradasSaidasForm;
use App\Models\EntradasSaidasModel;
use App\Services\Tenant\EntradaSaidaService;
use App\Services\Tenant\FormaPagamentoService;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public int    $limit   = 10;
    public int    $offset  = 0;
    public string $orderBy = 'data_vencimento';
    public string $dir     = 'asc';
    public string $search  = '';

    public EntradasSaidasForm $form;
    public BancosForm         $bancosForm;

    public array $selectedsIds = [];

    public bool $selectPage = false;

    public bool $selectAll = false;

    public ?int $idBanco = null;


    public function updatedSearch($value): void
    {
        $this->resetPage();

        if (!empty($value)) {

            $this->selectAll = false;

            $this->selectPage = false;

            $this->selectedsIds = [];
        }
    }

    public function desfazerbaixa(mixed $id)
    {
        $this->form->desfazerBaixa($id);

        $this->dispatch(EntradasSaidasForm::EVENT_PERSISTED, persistence: \App\Enums\Persistence::UPDATE->value);

        Flux::toast('Baixa desfeita com sucesso!', variant: 'success');
    }

    public function updatedSelectPage($value): void
    {
        $this->selectedsIds = $value ? $this->entradaSaida->pluck('id')->toArray() : [];
    }

    public function getEntradaSaidaProperty()
    {
        $filters = [
            'idBanco' => $this->idBanco,
        ];


        $filterPaginateRequest = (new FilterPaginateRequest())->merge(
            $this->only('limit', 'offset', 'orderBy', 'dir', 'search')
        );

        return (new EntradaSaidaService())->findAll($filterPaginateRequest, $filters);
    }

    public function getAllIds(): array
    {
        return EntradasSaidasModel::query()->where("removido", "=", false)->select("id")->pluck('id')->toArray();
    }


    #[On(EntradasSaidasForm::EVENT_PERSISTED)]
    public function with(?string $persistence = null, ?array $entradaSaida = null): array
    {
        if (!empty($persistence)) {

            $this->selectedsIds = [];

            $this->selectPage = false;

            $this->selectAll = false;
        }

        $bancos = \App\Models\BancosModel::query()->get();


        $count = !empty($this->search) ? $this->entradaSaida->total() : count($this->getAllIds());

        return [
            'entradaSaidaPendente' => $this->entradaSaida->where('data_pagamento', null),
            'entradaSaidaPago'     => $this->entradaSaida->where('data_pagamento', '!=', null),
            'saldoBancario'        => $this->entradaSaida
                ->map(function ($item) {
                    return $item->banco;
                })
                ->filter()
                ->unique('id')
                ->sum('saldo'),
            'bancos'               => $bancos,
            'count'                => $count,
        ];
    }

}; ?>

<div>
    <flux:button class="mb-4" variant="primary" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\EntradasSaidasForm::PATH_COMPONENT_FORM_CREATE_AND_UPDATE }}',
                                                                                    '{{ \App\Livewire\Forms\EntradasSaidasForm::EVENT_NAME_SHOW_MODAL_CREATE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\EntradasSaidasForm::MODAL_NAME_CREATE }}'
                                                                                    })">
        + Lançar Entrada/Saida
    </flux:button>

    <flux:select variant="listbox" placeholder="Selecione o banco" label="Banco"
                 wire:model.live="idBanco"
                 name="idBanco">
        <flux:select.option value="" selected>Todos os Bancos</flux:select.option>
        @if(count($bancos) > 0)

            @foreach($bancos as $banco)
                <flux:select.option value="{{ $banco->id }}">{{ $banco->nome }}</flux:select.option>
            @endforeach
        @else
            <flux:text class="m-2">Nenhuma categoria disponível</flux:text>
        @endif

    </flux:select>

    <flux:accordion class="mt-4">
        <flux:accordion.item expanded>
            <flux:accordion.heading>

                <flux:text class="text-accent">Movimentos Pagos</flux:text>

                <hr class="w-full h-px text-accent">
            </flux:accordion.heading>

            <flux:accordion.content>
                @if($entradaSaidaPago->count() > 0)

                    <div class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded-2xl">


                        <flux:table class="justify-between">


                            <flux:table.columns>

                                <flux:table.column>Ações</flux:table.column>
                                <flux:table.column>Data Vencimento</flux:table.column>
                                <flux:table.column>Tipo</flux:table.column>
                                <flux:table.column>Descricao</flux:table.column>
                                <flux:table.column>Categoria</flux:table.column>
                                <flux:table.column>Valor</flux:table.column>
                                <flux:table.column>Valor Pago</flux:table.column>
                                <flux:table.column>Data Pagamento</flux:table.column>
                                <flux:table.column>Saldo</flux:table.column>


                            </flux:table.columns>

                            <flux:table.rows>


                                {{--                                @php--}}
                                {{--                                    $saldoBancario = (float)$saldoBancario - (float)$entradaSaidaPago->sum('valor_pago') + (float)$entradaSaidaPago->first()->valor_pago;--}}
                                {{--                                @endphp--}}


                                @foreach ($entradaSaidaPago as $item)
                                    <flux:table.row :key="$item->id">
                                        <flux:table.cell
                                            class="whitespace-nowrap">
                                            <flux:button variant="primary" color="red" icon="banknote-x" size="xs"
                                                         class="cursor-pointer"
                                                         wire:click="desfazerbaixa({{$item->id}})"
                                                         tooltip="Defazer Baixa">

                                            </flux:button>

                                        </flux:table.cell>
                                        <flux:table.cell class=" items-center gap-3">
                                            {{ Helper::formatarDataPtBr($item->data_vencimento) }}
                                        </flux:table.cell>

                                        <flux:table.cell>
                                            <flux:badge
                                                color="{{ $item->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? 'green' : 'red' }}">
                                                {{ $item->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? 'Entrada' : 'Saída' }}
                                            </flux:badge>
                                        </flux:table.cell>

                                        <flux:table.cell class=" items-center gap-3">
                                            {{ $item->descricao ?? $item->categoria->nome }}
                                        </flux:table.cell>

                                        <flux:table.cell class=" items-center gap-3">
                                            {{ $item->categoria->nome }}
                                        </flux:table.cell>

                                        <flux:table.cell class="  items-center gap-3">
                                            <flux:text
                                                class="{{ $item->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? 'text-green-700' : 'text-red-700' }}">
                                                R$ {{Helper::formatarValorMonetarioPtBr($item->valor_original)}}</flux:text>
                                        </flux:table.cell>

                                        <flux:table.cell class="items-center gap-3">
                                            <flux:text
                                                class="{{ $item->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? 'text-green-700' : 'text-red-700' }}">
                                                R$ {{ Helper::formatarValorMonetarioPtBr($item->valor_pago)}}</flux:text>
                                        </flux:table.cell>

                                        <flux:table.cell class=" items-center gap-3">
                                            {{ Helper::formatarDataPtBr($item->data_pagamento)}}
                                        </flux:table.cell>

                                        <flux:table.cell class=" items-center gap-3">
                                            {{--                                            R$ {{ Helper::formatarValorMonetarioPtBr($saldoBancario += $item->valor_pago) }}--}}
                                            R$ {{ Helper::formatarValorMonetarioPtBr($saldoBancario += ($item->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? $item->valor_original : -$item->valor_original))  }}

                                        </flux:table.cell>


                                    </flux:table.row>
                                @endforeach
                                <flux:table.row>
                                    <flux:table.cell class=" items-center gap-3">
                                        Saldo:
                                    </flux:table.cell>

                                    <flux:table.cell>

                                    </flux:table.cell>

                                    <flux:table.cell class=" items-center gap-3">

                                    </flux:table.cell>

                                    <flux:table.cell class=" items-center gap-3">

                                    </flux:table.cell>

                                    <flux:table.cell class=" items-center gap-3">

                                    </flux:table.cell>

                                    <flux:table.cell class=" items-center gap-3">

                                    </flux:table.cell>

                                    <flux:table.cell class=" items-center gap-3">

                                    </flux:table.cell>


                                    <flux:table.cell>

                                    </flux:table.cell>
                                    <flux:table.cell class=" items-center gap-3">
                                        <b>R$ {{ Helper::formatarValorMonetarioPtBr($saldoBancario)  }}</b>
                                    </flux:table.cell>


                                </flux:table.row>
                            </flux:table.rows>
                        </flux:table>
                    </div>
                @else

                    <div
                        class="w-full text-center py-3 rounded-lg border-2 border-accent">
                        <p class="font-semibold text-accent">
                            Nenhum registro encontrado.
                        </p>
                    </div>

                @endif
            </flux:accordion.content>

            <flux:accordion.item expanded>
                <flux:accordion.heading>

                    <flux:text class="text-accent">Movimentos Previstos</flux:text>

                    <hr class="w-full h-px text-accent">

                </flux:accordion.heading>
                <flux:accordion.content>
                    @if($entradaSaidaPendente->count() > 0)

                        <div class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded-2xl">


                            <flux:table class="justify-between">


                                <flux:table.columns>

                                    <flux:table.column>Ações</flux:table.column>
                                    <flux:table.column>Data Vencimento</flux:table.column>
                                    <flux:table.column>Tipo</flux:table.column>
                                    <flux:table.column>Descricao</flux:table.column>
                                    <flux:table.column>Categoria</flux:table.column>
                                    <flux:table.column>Valor</flux:table.column>
                                    <flux:table.column>Valor Pago</flux:table.column>
                                    <flux:table.column>Data Pagamento</flux:table.column>
                                    <flux:table.column>Saldo previsto</flux:table.column>

                                </flux:table.columns>

                                <flux:table.rows>
                                    @foreach ($entradaSaidaPendente as $item)

                                        <flux:table.row :key="$item->id">

                                            <flux:table.cell
                                                class="whitespace-nowrap">
                                                <flux:button variant="primary" color="green" icon="banknotes" size="xs"
                                                             class="cursor-pointer"
                                                             wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\EntradasSaidasForm::PATH_COMPONENT_FORM_DAR_BAIXA }}',
                                                                                    '{{ \App\Livewire\Forms\EntradasSaidasForm::EVENT_NAME_SHOW_MODAL_DAR_BAIXA }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\EntradasSaidasForm::MODAL_NAME_DAR_BAIXA }}',
                                                                                        id: '{{ $item->id }}'
                                                                                    }
                                                                                )" tooltip="Dar Baixa">

                                                </flux:button>
                                                {{--                                <flux:modal.trigger name="delete-cliente" >--}}
                                                <flux:button icon="trash" variant="danger" size="xs"
                                                             class="cursor-pointer" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\EntradasSaidasForm::PATH_COMPONENT_FORM_REMOVE }}',
                                                                                    '{{ \App\Livewire\Forms\EntradasSaidasForm::EVENT_NAME_SHOW_MODAL_REMOVE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\EntradasSaidasForm::MODAL_NAME_REMOVE }}',
                                                                                        id: '{{ $item->id }}'
                                                                                    }
                                                                                )" tooltip="Remover"></flux:button>
                                                {{--                                </flux:modal.trigger>--}}
                                            </flux:table.cell>
                                            <flux:table.cell class=" items-center gap-3">
                                                {{ Helper::formatarDataPtBr($item->data_vencimento) }}
                                            </flux:table.cell>

                                            <flux:table.cell>
                                                <flux:badge
                                                    color="{{ $item->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? 'green' : 'red' }}">
                                                    {{ $item->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? 'Entrada' : 'Saída' }}
                                                </flux:badge>
                                            </flux:table.cell>

                                            <flux:table.cell class=" items-center gap-3">
                                                {{ $item->descricao ?? $item->categoria->nome }}
                                            </flux:table.cell>

                                            <flux:table.cell class=" items-center gap-3">
                                                {{ $item->categoria->nome }}
                                            </flux:table.cell>

                                            <flux:table.cell class=" items-center gap-3">
                                                <flux:text
                                                    class="{{ $item->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? 'text-green-700' : 'text-red-700' }}">
                                                    R$ {{ $item->valor_original ? Helper::formatarValorMonetarioPtBr($item->valor_original) : '0,00'}}</flux:text>
                                            </flux:table.cell>

                                            <flux:table.cell class=" items-center gap-3">
                                                R$ {{ $item->valor_pago ? Helper::formatarValorMonetarioPtBr($item->valor_pago) : '0,00' }}
                                            </flux:table.cell>

                                            <flux:table.cell class=" items-center gap-3">
                                                {{ $item->data_pagamento ?? '-' }}
                                            </flux:table.cell>

                                            <flux:table.cell class=" items-center gap-3">
                                                R$ {{ Helper::formatarValorMonetarioPtBr($saldoBancario += ($item->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? $item->valor_original : -$item->valor_original))  }}
                                            </flux:table.cell>


                                        </flux:table.row>
                                    @endforeach
                                    <flux:table.row>
                                        <flux:table.cell class=" items-center gap-3">
                                            Saldo Previsto:
                                        </flux:table.cell>

                                        <flux:table.cell>

                                        </flux:table.cell>

                                        <flux:table.cell class=" items-center gap-3">

                                        </flux:table.cell>

                                        <flux:table.cell class=" items-center gap-3">

                                        </flux:table.cell>

                                        <flux:table.cell class=" items-center gap-3">

                                        </flux:table.cell>

                                        <flux:table.cell class=" items-center gap-3">

                                        </flux:table.cell>

                                        <flux:table.cell class=" items-center gap-3">

                                        </flux:table.cell>


                                        <flux:table.cell>

                                        </flux:table.cell>
                                        <flux:table.cell class=" items-center gap-3">
                                            <b>R$ {{ Helper::formatarValorMonetarioPtBr($saldoBancario)  }}</b>
                                        </flux:table.cell>


                                    </flux:table.row>
                                </flux:table.rows>


                            </flux:table>
                        </div>



                        {{-- FIM TABELA --}}
                    @else

                        <div
                            class="w-full text-center py-3 rounded-lg border-2 border-accent">
                            <p class="font-semibold text-accent">
                                Nenhum registro encontrado.
                            </p>
                        </div>
                    @endif
                </flux:accordion.content>
            </flux:accordion.item>

        </flux:accordion.item>

    </flux:accordion>

    <div class="mb-4">


    </div>


    {{-- FIM TABELA --}}

    {{-- INICIO TABELA --}}


</div>
