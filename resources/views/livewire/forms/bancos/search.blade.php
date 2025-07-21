<?php

use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;

use App\Livewire\Forms\BancosForm;
use App\Models\BancosModel;
use App\Services\Tenant\BancoService;
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

    public BancosForm $form;

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
        $this->selectedsIds = $value ? $this->bancos->pluck('id')->toArray() : [];
    }

    public function getBancosProperty()
    {
        $filters = [];

        $filterPaginateRequest = (new FilterPaginateRequest())->merge(
            $this->only('limit', 'offset', 'orderBy', 'dir', 'search')
        );

        return (new BancoService())->findAll($filterPaginateRequest, $filters);
    }

    public function getAllIds(): array
    {
        return BancosModel::query()->where("removido", "=", false)->select("id")->pluck('id')->toArray();
    }


    #[On(BancosForm::EVENT_PERSISTED)]
    public function with(?string $persistence = null, ?array $banco = null): array
    {
        if (!empty($persistence)) {

            $this->selectedsIds = [];

            $this->selectPage = false;

            $this->selectAll = false;
        }

        $count = !empty($this->search) ? $this->bancos->total() : count($this->getAllIds());

        return [
            'bancos' => $this->bancos,
            'count'  => $count,
        ];
    }

}; ?>
<div>
    <flux:button class="mb-4" variant="primary" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\BancosForm::PATH_COMPONENT_FORM_CREATE_AND_UPDATE }}',
                                                                                    '{{ \App\Livewire\Forms\BancosForm::EVENT_NAME_SHOW_MODAL_CREATE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\BancosForm::MODAL_NAME_CREATE }}'
                                                                                    })">
        + Novo Banco
    </flux:button>
    {{-- INICIO TABELA --}}
    @if($bancos->count() > 0)

        <div class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded-2xl">

            <flux:table class="justify-between" :paginate="$this->bancos">
                <flux:table.columns>

                    <flux:table.column>Nome</flux:table.column>
                    <flux:table.column>Saldo</flux:table.column>
                    <flux:table.column>Acoes</flux:table.column>


                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->bancos as $banco)
                        <flux:table.row :key="$banco->id">
                            <flux:table.cell class=" items-center gap-3">
                                {{ $banco->nome }}
                            </flux:table.cell>
                            <flux:table.cell class=" items-center gap-3">
                                {{ $banco->saldo }}
                            </flux:table.cell>

                            <flux:table.cell
                                class="whitespace-nowrap">
                                <flux:button variant="outline" icon="pencil" size="xs"
                                             wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\BancosForm::PATH_COMPONENT_FORM_CREATE_AND_UPDATE }}',
                                                                                    '{{ \App\Livewire\Forms\BancosForm::EVENT_NAME_SHOW_MODAL_UPDATE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\BancosForm::MODAL_NAME_UPDATE }}',
                                                                                        id: '{{ $banco->id }}'
                                                                                    }
                                                                                )">
                                    Editar
                                </flux:button>
                                {{--                                <flux:modal.trigger name="delete-cliente" >--}}
                                <flux:button icon="trash" variant="danger" size="xs" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\BancosForm::PATH_COMPONENT_FORM_REMOVE }}',
                                                                                    '{{ \App\Livewire\Forms\BancosForm::EVENT_NAME_SHOW_MODAL_REMOVE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\BancosForm::MODAL_NAME_REMOVE }}',
                                                                                        id: '{{ $banco->id }}'
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
