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
    <div class="mb-4">

        <x-primary-button
            x-data
            wire:click="$dispatchTo(
                        '{{ ServicosForm::PATH_COMPONENT_FORM_CREATE_AND_UPDATE }}',
                        '{{ ServicosForm::EVENT_NAME_SHOW_MODAL_CREATE }}',
                        { modalName: '{{ ServicosForm::MODAL_NAME_CREATE }}'}
                    )"
            :disabled="count($selectedsIds) > 0"
        >
            {{ __('+ Novo serviço') }}
        </x-primary-button>
    </div>
    @if($servicos->count() > 0)
        {{-- INICIO TABELA --}}
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
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Valor</th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Ação</th>
                        </tr>

                        </thead>

                        <tbody>


                        @foreach($servicos as $servico)

                            <tr
                                wire:key="{{ $servico->id }}"
                                class="border-b border-smartserv-color-azul-medio-500 dark:border-smartserv-color-verde-limao-500"
                            >

                                <td class="px-4 py-3">{{ $servico->nome }}</td>
                                <td class="px-4 py-3">R$ {{ Helper::formatarValorMonetarioPtBr($servico->valor) }}</td>

                                <td class="px-4 py-3 whitespace-nowrap">


                                    <x-edit-button-table
                                        :data-tooltip-target="'tooltip-alterar-servico' . $servico->id"
                                        x-data
                                        wire:click="$dispatchTo(
                                                                                '{{ ServicosForm::PATH_COMPONENT_FORM_CREATE_AND_UPDATE }}',
                                                                                '{{ ServicosForm::EVENT_NAME_SHOW_MODAL_UPDATE }}',
                                                                                {
                                                                                    modalName: '{{ ServicosForm::MODAL_NAME_UPDATE }}',
                                                                                    id: '{{ $servico->id }}'
                                                                                }
                                                                            )"
                                    />
                                    <x-tooltip
                                        :id_tooltip="'tooltip-alterar-servico' . $servico->id"
                                        :text="__('Editar')"/>

                                    <x-delete-button-table
                                        :data-tooltip-target="'tooltip-remover-servico' . $servico->id"
                                        x-data
                                        wire:click="$dispatchTo(
                                                                                '{{ ServicosForm::PATH_COMPONENT_FORM_REMOVE }}',
                                                                                '{{ ServicosForm::EVENT_NAME_SHOW_MODAL_REMOVE }}',
                                                                                {
                                                                                    modalName: '{{ ServicosForm::MODAL_NAME_REMOVE }}',
                                                                                    id: '{{ $servico->id }}'
                                                                                }
                                                                            )"
                                    />
                                    <x-tooltip
                                        :id_tooltip="'tooltip-remover-servico' . $servico->id"
                                        :text="__('Remover')"/>


                                </td>

                            </tr>

                        @endforeach


                        </tbody>

                    </table>

                </div>

            </div>

        </section>
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
