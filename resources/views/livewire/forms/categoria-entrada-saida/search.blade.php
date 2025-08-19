<?php

use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;

use App\Livewire\Forms\CategoriasEntradaSaidaForm;
use App\Models\CategoriaEntradaSaidaModel;
use App\Services\Tenant\CategoriaEntradaSaidaService;
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

    public CategoriasEntradaSaidaForm $form;

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
        $this->selectedsIds = $value ? $this->categorias->pluck('id')->toArray() : [];
    }

    public function getCategoriasProperty()
    {
        $filters = [];

        $filterPaginateRequest = (new FilterPaginateRequest())->merge(
            $this->only('limit', 'offset', 'orderBy', 'dir', 'search')
        );

        return (new CategoriaEntradaSaidaService())->findAll($filterPaginateRequest, $filters);
    }

    public function getAllIds(): array
    {
        return CategoriaEntradaSaidaModel::query()->where("removido", "=", false)->select("id")->pluck('id')->toArray();
    }


    #[On(CategoriasEntradaSaidaForm::EVENT_PERSISTED)]
    public function with(?string $persistence = null, ?array $categoria = null): array
    {
        if (!empty($persistence)) {

            $this->selectedsIds = [];

            $this->selectPage = false;

            $this->selectAll = false;
        }

        $count = !empty($this->search) ? $this->categorias->total() : count($this->getAllIds());

        return [
            'categorias' => $this->categorias,
            'count'      => $count,
        ];
    }

}; ?>
<div>
    <flux:button class="mb-4" variant="primary" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\CategoriasEntradaSaidaForm::PATH_COMPONENT_FORM_CREATE_AND_UPDATE }}',
                                                                                    '{{ \App\Livewire\Forms\CategoriasEntradaSaidaForm::EVENT_NAME_SHOW_MODAL_CREATE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\CategoriasEntradaSaidaForm::MODAL_NAME_CREATE }}'
                                                                                    })">
        + Nova Categoria
    </flux:button>
    {{-- INICIO TABELA --}}
    @if($categorias->count() > 0)

        <div class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded-2xl">

            <flux:table class="justify-between" :paginate="$this->categorias">
                <flux:table.columns>

                    <flux:table.column>Nome</flux:table.column>
                    <flux:table.column>Tipo</flux:table.column>

                    <flux:table.column>Acoes</flux:table.column>


                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->categorias as $categoria)
                        <flux:table.row :key="$categoria->id">
                            <flux:table.cell>
                                {{ $categoria->nome }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge color="{{ $categoria->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? 'green' : 'red' }}">
                                    {{ $categoria->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? 'Entrada' : 'Saída' }}
                                </flux:badge>
                            </flux:table.cell>
                            @if($categoria->id > 0)
                                <flux:table.cell
                                    class="whitespace-nowrap">
                                    <flux:button variant="outline" icon="pencil" size="xs"
                                                 wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\CategoriasEntradaSaidaForm::PATH_COMPONENT_FORM_CREATE_AND_UPDATE }}',
                                                                                    '{{ \App\Livewire\Forms\CategoriasEntradaSaidaForm::EVENT_NAME_SHOW_MODAL_UPDATE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\CategoriasEntradaSaidaForm::MODAL_NAME_UPDATE }}',
                                                                                        id: '{{ $categoria->id }}'
                                                                                    }
                                                                                )">
                                        Editar
                                    </flux:button>
                                    {{--                                <flux:modal.trigger name="delete-cliente" >--}}
                                    <flux:button icon="trash" variant="danger" size="xs" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\CategoriasEntradaSaidaForm::PATH_COMPONENT_FORM_REMOVE }}',
                                                                                    '{{ \App\Livewire\Forms\CategoriasEntradaSaidaForm::EVENT_NAME_SHOW_MODAL_REMOVE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\CategoriasEntradaSaidaForm::MODAL_NAME_REMOVE }}',
                                                                                        id: '{{ $categoria->id }}'
                                                                                    }
                                                                                )">
                                        Excluir
                                    </flux:button>
                                    {{--                                </flux:modal.trigger>--}}
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
