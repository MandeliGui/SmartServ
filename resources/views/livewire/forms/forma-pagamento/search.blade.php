<?php

use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;

use App\Livewire\Forms\FormaPagamentoForm;
use App\Models\FormaPagamentoModel;
use App\Services\Tenant\FormaPagamentoService;
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

    public FormaPagamentoForm $form;

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
        $this->selectedsIds = $value ? $this->formasPagamento->pluck('id')->toArray() : [];
    }

    public function getFormasPagamentoProperty()
    {
        $filters = [];

        $filterPaginateRequest = (new FilterPaginateRequest())->merge(
            $this->only('limit', 'offset', 'orderBy', 'dir', 'search')
        );

        return (new FormaPagamentoService())->findAll($filterPaginateRequest, $filters);
    }

    public function getAllIds(): array
    {
        return FormaPagamentoModel::query()->where("removido", "=", false)->select("id")->pluck('id')->toArray();
    }


    #[On(FormaPagamentoForm::EVENT_PERSISTED)]
    public function with(?string $persistence = null, ?array $formaPagamento = null): array
    {
        if (!empty($persistence)) {

            $this->selectedsIds = [];

            $this->selectPage = false;

            $this->selectAll = false;
        }

        $count = !empty($this->search) ? $this->formasPagamento->total() : count($this->getAllIds());

        return [
            'formasPagamento' => $this->formasPagamento,
            'count'           => $count,
        ];
    }

}; ?>
<div>
    <flux:button class="mb-4" tooltip="teste" variant="primary" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\FormaPagamentoForm::PATH_COMPONENT_FORM_CREATE_AND_UPDATE }}',
                                                                                    '{{ \App\Livewire\Forms\FormaPagamentoForm::EVENT_NAME_SHOW_MODAL_CREATE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\FormaPagamentoForm::MODAL_NAME_CREATE }}'
                                                                                    })">
        + Nova Forma Pagamento
    </flux:button>
    {{-- INICIO TABELA --}}
    @if($formasPagamento->count() > 0)

        <div class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded-2xl">

            <flux:table class="justify-between" :paginate="$this->formasPagamento">
                <flux:table.columns>

                    <flux:table.column>Nome</flux:table.column>
                    <flux:table.column>Acoes</flux:table.column>


                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->formasPagamento as $formaPagamento)
                        <flux:table.row :key="$formaPagamento->id">
                            <flux:table.cell class="flex items-center gap-3">
                                {{ $formaPagamento->nome }}
                            </flux:table.cell>

                            <flux:table.cell
                                class="whitespace-nowrap">
                                <flux:button variant="outline" icon="pencil" size="xs"
                                             wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\FormaPagamentoForm::PATH_COMPONENT_FORM_CREATE_AND_UPDATE }}',
                                                                                    '{{ \App\Livewire\Forms\FormaPagamentoForm::EVENT_NAME_SHOW_MODAL_UPDATE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\FormaPagamentoForm::MODAL_NAME_UPDATE }}',
                                                                                        id: '{{ $formaPagamento->id }}'
                                                                                    }
                                                                                )">
                                    Editar
                                </flux:button>
                                {{--                                <flux:modal.trigger name="delete-cliente" >--}}
                                <flux:button icon="trash" variant="danger" size="xs" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\FormaPagamentoForm::PATH_COMPONENT_FORM_REMOVE }}',
                                                                                    '{{ \App\Livewire\Forms\FormaPagamentoForm::EVENT_NAME_SHOW_MODAL_REMOVE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\FormaPagamentoForm::MODAL_NAME_REMOVE }}',
                                                                                        id: '{{ $formaPagamento->id }}'
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
