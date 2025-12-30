<?php

use App\Enums\StatusEntradaSaida;
use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Livewire\Forms\OrdemServicoForm;
use App\Models\OrdemServicoModel;
use App\Services\Tenant\OrdemServicoService;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public int    $limit   = 10;
    public int    $offset  = 0;
    public string $orderBy = 'codigo';
    public string $dir     = 'desc';
    public string $search  = '';


    public OrdemServicoForm $form;

    public array $selectedsIds = [];

    public bool $selectPage = false;

    public bool $selectAll = false;


    public function updatedSearch($value): void
    {
        $this->resetPage();

        if (!empty($value)) {

            $this->selectAll = false;

            $this->selectPage = false;

            $this->selectedsIds = [];
        }
    }

    public function updatedSelectPage($value): void
    {
        $this->selectedsIds = $value ? $this->ordensServico->pluck('id')->toArray() : [];
    }

    public function getOrdensServicoProperty()
    {

        $filters = [];

        $filterPaginateRequest = (new FilterPaginateRequest())->merge(
            $this->only('limit', 'offset', 'orderBy', 'dir', 'search')
        );

        return (new OrdemServicoService())->findAll($filterPaginateRequest, $filters);
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {

            $this->selectPage = true;

            $this->selectedsIds = OrdemServicoModel::query()->where("removido", "=", false)->select("id")->pluck('id')->toArray();

        } else {

            $this->selectPage = false;

            $this->selectedsIds = [];
        }
    }

    public function getAllIds(): array
    {
        return OrdemServicoModel::query()->where("removido", "=", false)->select("id")->pluck('id')->toArray();
    }

    #[On(OrdemServicoForm::EVENT_PERSISTED)]
    public function with(?string $persistence = null, ?array $ordemServico = null): array
    {
        if (!empty($persistence)) {

            $this->selectedsIds = [];

            $this->selectPage = false;

            $this->selectAll = false;
        }

//        $count = !empty($this->search) ? $this->atendente->total() : count($this->getAllIds());


        return [
            'ordensServico' => $this->ordensServico,
//            'count'         => $count,
        ];
    }

    public function toggleSelection($id): void
    {
        if (in_array($id, $this->selectedsIds)) {
            $this->selectedsIds = array_diff($this->selectedsIds, [$id]);
        } else {
            $this->selectedsIds = [$id]; // Desmarca todos e seleciona apenas o novo
        }
    }

    public function abrirDetalhes(int $id): void
    {
        $this->redirectRoute('ordem-servico.editar', $id, navigate: true);
    }

}; ?>
<div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">

        <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Pesquisar (Número OS, Cliente, Telefone)"/>
    </div>
    <br>


    <flux:button class="mb-4" variant="primary" href="{{route('ordem-servico.novo')}}" wire:navigate>
        + Nova Ordem de Servico
    </flux:button>
    {{-- INICIO TABELA --}}

    @if($ordensServico->count() > 0)

        <div class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded-2xl">

            <flux:table class="" :paginate="$this->ordensServico">
                <flux:table.columns>

                    <flux:table.column>Numero</flux:table.column>
                    <flux:table.column>Situação</flux:table.column>
                    <flux:table.column>Cliente</flux:table.column>
                    <flux:table.column>Telefone</flux:table.column>
                    <flux:table.column>Valor Total</flux:table.column>
                    <flux:table.column>Ações</flux:table.column>
                    {{--                    <flux:table.column>Situação Pagamento</flux:table.column>--}}


                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->ordensServico as $ordemServico)

                        <flux:table.row :key="$ordemServico->id"
                                        class="cursor-pointer {{ in_array($ordemServico->id, $this->selectedsIds) ? 'dark:bg-accent bg-amber-500' : '' }}"
                                        wire:click="toggleSelection({{ $ordemServico->id }})"
                                        wire:dblclick="abrirDetalhes({{$ordemServico->id}})">
                            <flux:table.cell class="">
                                {{ $ordemServico->codigo }}
                            </flux:table.cell>

                            <flux:table.cell class="">

                                @if($ordemServico->status == \App\Enums\StatusOrdemServico::PENDENTE->value)
                                    <flux:badge size="lg" color="amber">Pendente</flux:badge>

                                @elseif($ordemServico->status == \App\Enums\StatusOrdemServico::EM_ANDAMENTO->value)
                                    <flux:badge size="lg" color="sky">Em Andamento</flux:badge>

                                @elseif($ordemServico->status == \App\Enums\StatusOrdemServico::FINALIZADO->value)
                                    <flux:badge size="lg" color="emerald">Finalizado</flux:badge>

                                @elseif($ordemServico->status == \App\Enums\StatusOrdemServico::CANCELADO->value)
                                    <flux:badge size="lg" color="red">Cancelado</flux:badge>

                                @endif
                            </flux:table.cell>

                            <flux:table.cell class="">
                                {{ $ordemServico->cliente->pessoa->nomeFantasia ?: $ordemServico->cliente->pessoa->nomeRazaoSocial }}
                            </flux:table.cell>

                            <flux:table.cell
                                class="whitespace-nowrap">{{ Helper::formatarPhoneBR($ordemServico->cliente->pessoa->telefone) }}
                            </flux:table.cell>

                            <flux:table.cell class="">
                                R$ {{ Helper::formatarValorMonetarioPtBr($ordemServico->valorTotal ?? 0) }}
                            </flux:table.cell>


                            <flux:table.cell class=" ">
                                <div class="flex gap-1">

                                    <flux:button.group>
                                        <flux:button icon="document" variant="primary" color="rose" size="xs"
                                                     href="{{ route('ordem-servico.pdf', $ordemServico->id) }}" target="_blank"
                                                     @click.stop>
                                            Gerar PDF
                                        </flux:button>

                                        <flux:dropdown>
                                            <flux:button icon="chevron-down" variant="primary" color="rose" size="xs"
                                                         @click.stop></flux:button>

                                            <flux:menu>
                                                <flux:menu.item icon="document"
                                                                @click.stop
                                                                href="{{ route('ordem_servico.pdf', $ordemServico->id, false) }}" target="_blank"
                                                >Gerar sem valor
                                                </flux:menu.item>
                                            </flux:menu>
                                        </flux:dropdown>


                                    </flux:button.group>
                                    <flux:button variant="outline" icon="pencil" size="xs"
                                                 href="{{route('ordem-servico.editar', $ordemServico->id)}}" wire:navigate
                                                 @click.stop>
                                        Visualizar
                                    </flux:button>
                                    @if($ordemServico->entradasSaidas->count() > 0)
                                        <flux:button :class="'opacity-50 cursor-not-allowed'" icon="trash" variant="danger" size="xs" tooltip="Não é possível excluir uma ordem de serviço que possui entradas/saídas vinculadas." @click.stop aria-disabled="true">

                                            Excluir
                                        </flux:button>
                                    @else
                                        <flux:button icon="trash" variant="danger" size="xs"
                                                     wire:click.stop="$dispatchTo(
                                         '{{ \App\Livewire\Forms\OrdemServicoForm::PATH_COMPONENT_FORM_REMOVE }}',
                                         '{{ \App\Livewire\Forms\OrdemServicoForm::EVENT_NAME_SHOW_MODAL_REMOVE }}',
                                         {
                                             modalName: '{{ \App\Livewire\Forms\OrdemServicoForm::MODAL_NAME_REMOVE }}',
                                             id: '{{ $ordemServico->id }}'
                                         }
                                     )">
                                            Excluir
                                        </flux:button>
                                    @endif


                                </div>
                                {{--                                </flux:modal.trigger>--}}
                            </flux:table.cell>

                            @if($ordemServico->status == \App\Enums\StatusOrdemServico::FINALIZADO->value)
                                @php

                                    $entradasSaidas = $ordemServico->entradasSaidas;
                                    $ordemServicoPaga = $entradasSaidas->sum('valorPago')  >= $ordemServico->valorTotal;

                                    $textColor = $ordemServicoPaga ? "text-green-400" : "text-amber-400";


                                @endphp
                                <flux:table.cell class="">
                                    <flux:tooltip>
                                        <flux:button size="sm" variant="ghost">
                                            @if(!$ordemServicoPaga)
                                                <flux:icon.clock/>
                                            @else
                                                <flux:icon.check-circle/>
                                            @endif
                                        </flux:button>

                                        <flux:tooltip.content class="max-w-[40rem] space-y-2">
                                            <div class="flex justify-between">
                                                <flux:text class="{{$textColor}}">{{$ordemServicoPaga ? "Paga" : "Pendente"}}</flux:text>

                                                @if($entradasSaidas->count() > 1)

                                                    <flux:text class="text-neutral-100">
                                                        {{ $entradasSaidas->where('status', StatusEntradaSaida::PAGO)->count() }}
                                                        de
                                                        {{ $entradasSaidas->count() }}
                                                    </flux:text>
                                                @endif

                                            </div>

                                            <flux:separator/>
                                            <flux:table>
                                                <flux:table.columns>

                                                    <flux:table.column>Data Vencimento</flux:table.column>
                                                    <flux:table.column>Situação</flux:table.column>
                                                    <flux:table.column>Data Pagamento</flux:table.column>

                                                </flux:table.columns>

                                                @foreach($entradasSaidas as $item)

                                                    <flux:table.rows>


                                                        <flux:table.row>
                                                            <flux:table.cell>{{Helper::formatarDataPtBr($item->data_vencimento)}}</flux:table.cell>
                                                            <flux:table.cell>
                                                                <flux:text class="{{StatusEntradaSaida::colors($item->status)['text']}}">{{ Str::ucfirst(StatusEntradaSaida::label($item->status))}} </flux:text>
                                                            </flux:table.cell>
                                                            <flux:table.cell>{{Helper::formatarDataPtBr($item->data_pagamento)}}</flux:table.cell>
                                                        </flux:table.row>


                                                    </flux:table.rows>
                                                @endforeach

                                            </flux:table>

                                        </flux:tooltip.content>
                                    </flux:tooltip>
                                </flux:table.cell>
                            @endif

                        </flux:table.row>
                    @endforeach
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
</div>
