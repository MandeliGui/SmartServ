<?php

use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Livewire\Forms\FornecedoresForm;
use App\Models\FornecedoresModel;
use App\Services\Tenant\FornecedoresService;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Flux\Flux;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public int    $limit   = 10;
    public int    $offset  = 0;
    public string $orderBy = 'nome_fantasia';
    public string $dir     = 'asc';
    public string $search  = '';

    public FornecedoresForm $form;

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
        $this->selectedsIds = $value ? $this->fornecedores->pluck('id')->toArray() : [];
    }

    public function getFornecedoresProperty()
    {
        $filters = [];

        $filterPaginateRequest = (new FilterPaginateRequest())->merge(
            $this->only('limit', 'offset', 'orderBy', 'dir', 'search')
        );

        return (new FornecedoresService())->findAll($filterPaginateRequest, $filters);
    }


    #[On(FornecedoresForm::EVENT_PERSISTED)]
    public function with(?string $persistence = null, ?array $fornecedor = null): array
    {
        $count = $this->fornecedores->total();


        return [
            'fornecedores' => $this->fornecedores,
            'count'        => $count,
        ];
    }

    public function teste($id)
    {

    }


}; ?>
<div>


    <flux:button href="{{ route('fornecedores.novo') }}" class="mb-4" variant="primary"
                 wire:navigate>+ Novo Fornecedor
    </flux:button>
    {{-- INICIO TABELA --}}
    @if($fornecedores->count() > 0)

        <div class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded-2xl">

            <flux:table class="" :paginate="$this->fornecedores">
                <flux:table.columns>
                    <flux:table.column>Nome</flux:table.column>
                    <flux:table.column>Telefone</flux:table.column>
                    <flux:table.column>Ações</flux:table.column>


                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->fornecedores as $fornecedor)
                        <flux:table.row :key="$fornecedor->id">
                            <flux:table.cell class="flex items-center gap-3">
                                {{ $fornecedor->razao_social }}
                            </flux:table.cell>

                            <flux:table.cell
                                class="whitespace-nowrap">{{ $fornecedor->telefone ? Helper::formatarPhoneBR($fornecedor->telefone) : '-' }}
                            </flux:table.cell>

                            <flux:table.cell class="flex items-center gap-3 ">
                                <flux:button variant="outline" icon="pencil" size="xs"
                                             wire:navigate href="{{ route('fornecedores.editar', $fornecedor->id) }}">
                                    Editar
                                </flux:button>
                                <flux:button icon="trash" variant="danger" size="xs" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\FornecedoresForm::PATH_COMPONENT_FORM_REMOVE }}',
                                                                                    '{{ \App\Livewire\Forms\FornecedoresForm::EVENT_NAME_SHOW_MODAL_REMOVE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\FornecedoresForm::MODAL_NAME_REMOVE }}',
                                                                                        id: '{{ $fornecedor->id }}'
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
