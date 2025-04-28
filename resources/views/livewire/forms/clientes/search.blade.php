<?php

use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Livewire\Forms\ClientesForm;
use App\Models\ClienteModel;
use App\Services\Tenant\ClienteService;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public int $limit = 10;
    public int $offset = 0;
    public string $orderBy = 'nomeFantasia';
    public string $dir = 'asc';
    public string $search = '';

    public ClientesForm $form;

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
        $this->selectedsIds = $value ? $this->clientes->pluck('id')->toArray() : [];
    }

    public function getClientesProperty()
    {
        $filters = [];

        $filterPaginateRequest = (new FilterPaginateRequest())->merge(
            $this->only('limit', 'offset', 'orderBy', 'dir', 'search')
        );

        return (new ClienteService())->findAll($filterPaginateRequest, $filters);
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {

            $this->selectPage = true;

            $this->selectedsIds = ClienteModel::query()->where("removido", "=", false)->select("id_cliente")->pluck('id_cliente')->toArray();

        } else {

            $this->selectPage = false;

            $this->selectedsIds = [];
        }
    }

    public function getAllIds(): array
    {
        return ClienteModel::query()->where("removido", "=", false)->select("idCliente")->pluck('idCliente')->toArray();
    }

    #[On(ClientesForm::EVENT_PERSISTED)]
    public function with(?string $persistence = null, ?array $cliente = null): array
    {
        if (!empty($persistence)) {

            $this->selectedsIds = [];

            $this->selectPage = false;

            $this->selectAll = false;
        }

        $count = !empty($this->search) ? $this->clientes->total() : count($this->getAllIds());

        return [
            'clientes' => $this->clientes,
            'count' => $count,
        ];
    }

}; ?>
<div>


    <flux:button href="{{ route('clientes.novo') }}" class="mb-4" tooltip="teste" variant="primary"
                 wire:navigate>+ Novo Cliente
    </flux:button>
    {{-- INICIO TABELA --}}
    @if($clientes->count() > 0)




        <div class="bg-neutral-800 p-4 rounded-2xl">

            <flux:table class="" :paginate="$this->clientes">
                <flux:table.columns>
                    <flux:table.column>Nome</flux:table.column>
                    <flux:table.column>Telefone</flux:table.column>


                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->clientes as $cliente)
                        <flux:table.row :key="$cliente->id">
                            <flux:table.cell class="flex items-center gap-3">
                                {{ $cliente->nomeRazaoSocial }}
                            </flux:table.cell>

                            <flux:table.cell class="whitespace-nowrap">{{ $cliente->telefone }}</flux:table.cell>


                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>



        {{-- FIM TABELA --}}
    @else

        <div
            class="w-full text-center py-3 bg-smartserv-color-primary-500 dark:bg-smartserv-color-dark-700 rounded-lg border-2 border-smartserv-color-primary-1000 dark:border-smartserv-color-primary-dark-1000">
            <p class="font-semibold font-smartserv-font-title text-smartserv-color-primary-1000 dark:text-smartserv-color-neutral-100">
                Nenhum registro encontrado.
            </p>
        </div>
    @endif

</div>
