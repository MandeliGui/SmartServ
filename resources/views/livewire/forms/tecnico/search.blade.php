<?php

use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Livewire\Forms\TecnicoForm;
use App\Models\TecnicoModel;
use App\Services\Tenant\TecnicoService;
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

    public TecnicoForm $form;

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
        $this->selectedsIds = $value ? $this->tecnicos->pluck('id')->toArray() : [];
    }

    public function getTecnicosProperty()
    {

        $filters = [];

        $filterPaginateRequest = (new FilterPaginateRequest())->merge(
            $this->only('limit', 'offset', 'orderBy', 'dir', 'search')
        );

        return (new TecnicoService())->findAll($filterPaginateRequest, $filters);
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {

            $this->selectPage = true;

            $this->selectedsIds = TecnicoModel::query()->where("removido", "=", false)->select("idTecnico")->pluck('idTecnico')->toArray();

        } else {

            $this->selectPage = false;

            $this->selectedsIds = [];
        }
    }

    public function getAllIds(): array
    {
        return TecnicoModel::query()->where("removido", "=", false)->select("idTecnico")->pluck('idTecnico')->toArray();
    }

    #[On(TecnicoForm::EVENT_PERSISTED)]
    public function with(?string $persistence = null, ?array $tecnico = null): array
    {
        if (!empty($persistence)) {

            $this->selectedsIds = [];

            $this->selectPage = false;

            $this->selectAll = false;
        }

        $count = !empty($this->search) ? $this->tecnico->total() : count($this->getAllIds());

//        dd($this->tecnicos)

        return [
            'tecnicos' => $this->tecnicos,
            'count'   => $count,
        ];
    }

}; ?>
<div>

    <x-button-redirect
        class="mb-4"
        href="{{route('tecnico.novo')}}">
        {{ __('+ Novo tecnico') }}
    </x-button-redirect>

        @if($tecnicos->count() > 0)
{{--             INICIO TABELA --}}
            <section class=" mt-4
        ">

                <div class="bg-white dark:bg-smartserv-color-dark-800 relative shadow-md rounded overflow-hidden">

                    <div class="overflow-x-auto">

                        <table
                            class="w-full text-sm text-left text-smartserv-color-dark-800 dark:text-smartserv-color-cinza-light-800">

                            <thead
                                class="font-smartserv-font-main text-xs text-smartserv-color-azul-medio-1000 uppercase bg-gray-100 dark:bg-smartserv-color-dark-900 "
                            >

                            <tr>

                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Nome</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Telefone</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Ação</th>
                            </tr>

                            </thead>

                            <tbody>


                            @foreach($tecnicos as $tecnico)

                                <tr
                                    wire:key="{{ $tecnico->id }}"
                                    class="border-b border-smartserv-color-azul-medio-500 dark:border-smartserv-color-verde-limao-500"
                                >

                                    <td class="px-4 py-3">{{ $tecnico->nomeRazaoSocial }}</td>
                                    <td class="px-4 py-3">{{ Helper::formatarPhoneBR($tecnico->telefone) }}</td>

                                    <td class="px-4 py-3 whitespace-nowrap">


                                        <x-edit-button-table
                                            :data-tooltip-target="'tooltip-alterar-tecnico' . $tecnico->id"
                                            x-data
                                            href="{{ route('tecnico.editar', ['id' => $tecnico->id]) }}"
                                            wire:navigate
                                        />
                                        <x-tooltip
                                            :id_tooltip="'tooltip-alterar-tecnico' . $tecnico->id"
                                            :text="__('Editar')"/>

                                        <x-delete-button-table
                                            :data-tooltip-target="'tooltip-remover-tecnico' . $tecnico->id"
                                            x-data
                                            wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\TecnicoForm::PATH_COMPONENT_FORM_REMOVE }}',
                                                                                    '{{ \App\Livewire\Forms\TecnicoForm::EVENT_NAME_SHOW_MODAL_REMOVE }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\TecnicoForm::MODAL_NAME_REMOVE }}',
                                                                                        id: '{{ $tecnico->id }}'
                                                                                    }
                                                                                )"
                                        />
                                        <x-tooltip
                                            :id_tooltip="'tooltip-remover-tecnico' . $tecnico->id"
                                            :text="__('Remover')"/>


                                    </td>

                                </tr>

                            @endforeach


                            </tbody>

                        </table>

                    </div>

                </div>

            </section>
{{--             FIM TABELA --}}
        @else

    <div
        class="w-full text-center py-3 bg-smartserv-color-primary-500 dark:bg-smartserv-color-dark-700 rounded-lg border-2 border-smartserv-color-primary-1000 dark:border-smartserv-color-primary-dark-1000">
        <p class="font-semibold font-smartserv-font-title text-smartserv-color-primary-1000 dark:text-smartserv-color-neutral-100">
            Nenhum registro encontrado.
        </p>
    </div>
        @endif

</div>
