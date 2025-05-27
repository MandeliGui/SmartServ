<?php

use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Livewire\Forms\AtendenteForm;
use App\Models\AtendenteModel;
use App\Services\Tenant\AtendenteService;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public int    $limit   = 10;
    public int    $offset  = 0;
    public string $orderBy = 'nomeRazaoSocial';
    public string $dir     = 'asc';
    public string $search  = '';

    public AtendenteForm $form;

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
        $this->selectedsIds = $value ? $this->atendentes->pluck('id')->toArray() : [];
    }

    public function getAtendentesProperty()
    {

        $filters = [];

        $filterPaginateRequest = (new FilterPaginateRequest())->merge(
            $this->only('limit', 'offset', 'orderBy', 'dir', 'search')
        );

        return (new AtendenteService())->findAll($filterPaginateRequest, $filters);
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {

            $this->selectPage = true;

            $this->selectedsIds = AtendenteModel::query()->where("removido", "=", false)->select("idAtendente")->pluck('idAtendente')->toArray();

        } else {

            $this->selectPage = false;

            $this->selectedsIds = [];
        }
    }

    public function getAllIds(): array
    {
        return AtendenteModel::query()->where("removido", "=", false)->select("idAtendente")->pluck('idAtendente')->toArray();
    }

    #[On(AtendenteForm::EVENT_PERSISTED)]
    public function with(?string $persistence = null, ?array $atendente = null): array
    {
        if (!empty($persistence)) {

            $this->selectedsIds = [];

            $this->selectPage = false;

            $this->selectAll = false;
        }

        $count = !empty($this->search) ? $this->atendente->total() : count($this->getAllIds());

//        dd($this->atendentes)

        return [
            'atendentes' => $this->atendentes,
            'count'      => $count,
        ];
    }

}; ?>
<div>

    <flux:button class="mb-4"  variant="primary" href="{{route('atendentes.novo')}}" wire:navigate>
        + Novo Atendente
    </flux:button>
    {{-- INICIO TABELA --}}

    @if($atendentes->count() > 0)

        <div class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded-2xl">

            <flux:table class="" :paginate="$this->atendentes">
                <flux:table.columns>

                    <flux:table.column>Nome</flux:table.column>
                    <flux:table.column>Telefone</flux:table.column>
                    <flux:table.column>Acoes</flux:table.column>


                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->atendentes as $atendente)
                        <flux:table.row :key="$atendente->id">
                            <flux:table.cell class="flex items-center gap-3">
                                {{ $atendente->nomeRazaoSocial }}
                            </flux:table.cell>

                            <flux:table.cell
                                    class="whitespace-nowrap">{{ Helper::formatarPhoneBR($atendente->telefone) }}
                            </flux:table.cell>


                            <flux:table.cell class="flex items-center gap-3 ">
                                <flux:button variant="outline" icon="pencil" size="xs"
                                             href="{{route('atendentes.editar', $atendente->id)}}" wire:navigate>
                                    Editar
                                </flux:button>
                                {{--                                <flux:modal.trigger name="delete-cliente" >--}}
                                <flux:button icon="trash" variant="danger" size="xs" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\AtendenteForm::PATH_COMPONENT_FORM_REMOVE }}',
                                                                                    '{{ \App\Livewire\Forms\AtendenteForm::EVENT_NAME_SHOW_MODAL_REMOVE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\AtendenteForm::MODAL_NAME_REMOVE }}',
                                                                                        id: '{{ $atendente->id }}'
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
