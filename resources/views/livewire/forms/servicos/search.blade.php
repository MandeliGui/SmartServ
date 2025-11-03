<?php

use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;

use App\Livewire\Forms\ServicosForm;
use App\Models\ServicosModel;
use App\Services\Tenant\ServicosService;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public int    $limit   = 10;
    public int    $offset  = 0;
    public string $orderBy = 'nome';
    public string $dir     = 'asc';
    public string $search  = '';

    public ServicosForm $form;

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
        $this->selectedsIds = $value ? $this->servicos->pluck('id')->toArray() : [];
    }

    public function getServicosProperty()
    {
        $filters = [];

        $filterPaginateRequest = (new FilterPaginateRequest())->merge(
            $this->only('limit', 'offset', 'orderBy', 'dir', 'search')
        );

        return (new ServicosService())->findAll($filterPaginateRequest, $filters);
    }

    public function getAllIds(): array
    {
        return ServicosModel::query()->where("removido", "=", false)->select("id")->pluck('id')->toArray();
    }


    #[On(ServicosForm::EVENT_PERSISTED)]
    public function with(?string $persistence = null, ?array $servico = null): array
    {
        if (!empty($persistence)) {

            $this->selectedsIds = [];

            $this->selectPage = false;

            $this->selectAll = false;
        }

        $count = !empty($this->search) ? $this->servicos->total() : count($this->getAllIds());

        return [
            'servicos' => $this->servicos,
            'count'    => $count,
        ];
    }

}; ?>
<div>
    <flux:button class="mb-4"  variant="primary" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\ServicosForm::PATH_COMPONENT_FORM_CREATE_AND_UPDATE }}',
                                                                                    '{{ \App\Livewire\Forms\ServicosForm::EVENT_NAME_SHOW_MODAL_CREATE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\ServicosForm::MODAL_NAME_CREATE }}'
                                                                                    })">
        + Novo Servico
    </flux:button>
    {{-- INICIO TABELA --}}
    @if($servicos->count() > 0)

        <div class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded-2xl">

            <flux:table class="" :paginate="$this->servicos">
                <flux:table.columns>
                    <flux:table.column>Codigo</flux:table.column>
                    <flux:table.column>Nome</flux:table.column>
                    <flux:table.column>Valor</flux:table.column>
                    <flux:table.column>Ações</flux:table.column>


                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->servicos as $servico)
                        <flux:table.row :key="$servico->id">
                            <flux:table.cell class="flex items-center gap-3">
                                {{ $servico->codigo }}
                            </flux:table.cell>

                            <flux:table.cell
                                    class="whitespace-nowrap">{{ $servico->nome }}
                            </flux:table.cell>

                            <flux:table.cell
                                    class="whitespace-nowrap">R$ {{ Helper::formatarValorMonetarioPtBr($servico->valor) }}
                            </flux:table.cell>

                            <flux:table.cell class="flex items-center gap-3 ">
                                <flux:button variant="outline" icon="pencil" size="xs"
                                             wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\ServicosForm::PATH_COMPONENT_FORM_CREATE_AND_UPDATE }}',
                                                                                    '{{ \App\Livewire\Forms\ServicosForm::EVENT_NAME_SHOW_MODAL_UPDATE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\ServicosForm::MODAL_NAME_UPDATE }}',
                                                                                        id: '{{ $servico->id }}'
                                                                                    }
                                                                                )">
                                    Editar
                                </flux:button>
                                {{--                                <flux:modal.trigger name="delete-cliente" >--}}
                                <flux:button icon="trash" variant="danger" size="xs" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\ServicosForm::PATH_COMPONENT_FORM_REMOVE }}',
                                                                                    '{{ \App\Livewire\Forms\ServicosForm::EVENT_NAME_SHOW_MODAL_REMOVE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\ServicosForm::MODAL_NAME_REMOVE }}',
                                                                                        id: '{{ $servico->id }}'
                                                                                    }
                                                                                )">
                                    Excluir
                                </flux:button>
                                {{--                                </flux:modal.trigger>--}}
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
