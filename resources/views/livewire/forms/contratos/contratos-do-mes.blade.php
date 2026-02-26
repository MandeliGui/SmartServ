<?php

use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Livewire\Forms\ContratoForm;
use App\Models\ContratosModel;
use App\Services\Tenant\ContratosService;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public int    $limit   = 10;
    public int    $offset  = 0;
    public string $orderBy = 'id';
    public string $dir     = 'asc';
    public string $search  = '';


    public ContratoForm $form;

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
        $this->selectedsIds = $value ? $this->contratos->pluck('id')->toArray() : [];
    }

    public function getContratosProperty()
    {
        $filters = [];

        $filterPaginateRequest = (new FilterPaginateRequest())->merge(
            $this->only('limit', 'offset', 'orderBy', 'dir', 'search')
        );

        return (new ContratosService())->findContratosDoMes($filterPaginateRequest, $filters);
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {

            $this->selectPage = true;

            $this->selectedsIds = ContratosModel::query()->where("removido", "=", false)->select("id")->pluck('id')->toArray();

        } else {

            $this->selectPage = false;

            $this->selectedsIds = [];
        }
    }

    public function getAllIds(): array
    {
        return ContratosModel::query()->where("removido", "=", false)->select("id")->pluck('id')->toArray();
    }

    #[On(ContratoForm::EVENT_PERSISTED)]
    public function with(?string $persistence = null, ?array $contrato = null): array
    {
        if (!empty($persistence)) {

            $this->selectedsIds = [];

            $this->selectPage = false;

            $this->selectAll = false;
        }

        $count = !empty($this->search) ? $this->contratos->total() : count($this->getAllIds());


        return [
            'contratos' => $this->contratos,
            'count'     => $count,
        ];
    }

    public function gerarOrdensServico()
    {
        $this->form->gerarOrdensServico($this->contratos->all());
    }


}; ?>
<div>
    <flux:button class="mb-4" variant="primary" wire:click.prevent="gerarOrdensServico">
        Gerar ordens de servico do mês
    </flux:button>

    @if($contratos->count() > 0)

        <div class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded-2xl">

            <flux:table class="" :paginate="$this->contratos">
                <flux:table.columns>

                    <flux:table.column>Cliente</flux:table.column>
                    <flux:table.column>Situacao</flux:table.column>
                    <flux:table.column>Data Inicio Contrato</flux:table.column>
                    <flux:table.column>Data Ultimo Atendimento</flux:table.column>
                    <flux:table.column>Periodicidade</flux:table.column>
                    <flux:table.column>Telefone</flux:table.column>
                    <flux:table.column>Valor Total</flux:table.column>
                    <flux:table.column>Situacao</flux:table.column>


                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($this->contratos as $contrato)

                        <flux:table.row :key="$contrato->id"
                                        class="{{ in_array($contrato->id, $this->selectedsIds) ? 'dark:bg-accent bg-amber-500' : '' }}">
                            <flux:table.cell class="">
                                {{ $contrato->cliente->pessoa->nomeFantasia ?: $contrato->cliente->pessoa->nomeRazaoSocial }}
                            </flux:table.cell>
                            <flux:table.cell class="">

                                @if($contrato->status == \App\Enums\StatusContrato::ATIVO->value)
                                    <flux:badge size="lg" color="emerald">Ativo</flux:badge>

                                @elseif($contrato->status == \App\Enums\StatusContrato::INATIVO->value)
                                    <flux:badge size="lg" color="red">Inativo</flux:badge>

                                @endif
                            </flux:table.cell>
                            <flux:table.cell class="">
                                {{ Helper::formatarDataPtBr($contrato->data_inicio_contrato) }}
                            </flux:table.cell>
                            <flux:table.cell class="">
                                {{ Helper::formatarDataPtBr($contrato->ordemServico->last()?->dataAbertura) ?? 'Não foi realizado atendimento ainda' }}
                            </flux:table.cell>
                            <flux:table.cell class="">

                                @if($contrato->periodicidade == \App\Enums\Periodicidade::MENSAL->value)
                                    <flux:badge size="lg" color="sky">Mensal</flux:badge>

                                @elseif($contrato->periodicidade == \App\Enums\Periodicidade::BIMESTRAL->value)
                                    <flux:badge size="lg" color="blue">Bimestral</flux:badge>

                                @elseif($contrato->periodicidade == \App\Enums\Periodicidade::TRIMESTRAL->value)
                                    <flux:badge size="lg" color="indigo">Trimestral</flux:badge>

                                @elseif($contrato->periodicidade == \App\Enums\Periodicidade::QUADRIMESTRAL->value)
                                    <flux:badge size="lg" color="violet">Quadrimestral</flux:badge>

                                @elseif($contrato->periodicidade == \App\Enums\Periodicidade::SEMESTRAL->value)
                                    <flux:badge size="lg" color="purple">Semestral</flux:badge>

                                @elseif($contrato->periodicidade == \App\Enums\Periodicidade::ANUAL->value)
                                    <flux:badge size="lg" color="fuchsia">Anual</flux:badge>

                                @endif
                            </flux:table.cell>


                            <flux:table.cell
                                class="whitespace-nowrap">{{ Helper::formatarPhoneBR($contrato->cliente->pessoa->telefone) }}
                            </flux:table.cell>

                            <flux:table.cell class="">
                                R$ {{ Helper::formatarValorMonetarioPtBr($contrato->valor ?? 0) }}
                            </flux:table.cell>
                            @php
                                if ($contrato->ordemServico->isNotEmpty()) {

                                    $ultimoAtendimento = Carbon::parse($contrato->ordemServico->last()->dataAbertura)->format('Y-m');
                                    $dataAtual         = Carbon::now()->format('Y-m');

                                    if ($ultimoAtendimento === $dataAtual) {
                                    $ordemJaGerada = true;
                                    }
                                }else{
                                    $ordemJaGerada = false;
                                }
                            @endphp
                            <flux:table.cell class="">
                                <flux:heading size="sm" class="{{ isset($ordemJaGerada) && $ordemJaGerada ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ isset($ordemJaGerada) && $ordemJaGerada ? 'Gerada' : 'Pendente' }}
                                </flux:heading>
                            </flux:table.cell>


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
