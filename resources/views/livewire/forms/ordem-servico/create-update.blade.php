<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\AdicionarMateriaisForm;
use App\Livewire\Forms\AdicionarServicosForm;
use App\Livewire\Forms\OrdemServicoForm;
use App\Models\OrdemServicoModel;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public OrdemServicoForm $form;
    public ?Persistence     $persistence = Persistence::CREATE;

    public float $valorMateriais = 0;
    public float $valorServicos  = 0;


    public function save(): void
    {


        if ($this->persistence == Persistence::UPDATE) {

            $cliente = $this->form->update();

            $this->dispatch(OrdemServicoForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, cliente: $cliente);

            $this->redirect(route('ordem-servico'), navigate: true);

        } else {

            try {

                $cliente = $this->form->create();


                $this->dispatch(OrdemServicoForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, cliente: $cliente);
                $this->redirect(route('ordem-servico'), navigate: true);
            } catch (Throwable $e) {
                throw $e;
            }

        }

    }

    #[On(AdicionarMateriaisForm::EVENT_PERSISTED)]
    public function atualizarMateriais($materiais)
    {

        $this->form->materiais = array_merge($this->form->materiais ?? [], $materiais);
    }

    #[On(AdicionarServicosForm::EVENT_PERSISTED)]
    public function atualizarServicos($servicos)
    {

        $this->form->servicos = array_merge($this->form->servicos ?? [], $servicos);
    }

    public function mount()
    {

        $this->form->codigo       = (string)(OrdemServicoModel::latest()->first() ? (int)OrdemServicoModel::latest()->first()->codigo + 1 : 1);
        $this->form->dataAbertura = now()->format('Y-m-d');
        $this->form->clientes     = \App\Models\ClienteModel::query()->get();


        $this->id = request()->route('id') ?? null;

        if ($this->id) {

            $this->persistence = Persistence::UPDATE;
            $this->form->setOrdemServico($this->id);

        }
    }


};
?>

<div x-data>
    <form wire:submit.prevent="save">
        <flux:text>Informações ordem de servico</flux:text>

        <hr class="w-full h-px bg-accent">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
            <div>

                <flux:heading class="mt-4">Nº Ordem de Serviço</flux:heading>
                <flux:heading size="xl"
                              class=" inline-block mt-2 text-accent-content strong bg-neutral-100 dark:bg-neutral-800 px-4 py-1 rounded">{{$form->codigo}}</flux:heading>
            </div>

            <flux:select label="Tipo"
                         wire:model="form.tipo"
                         placeholder="Selecione"
                         name="tipo"
            >

                <flux:select.option value="{{\App\Enums\TipoOrdemServico::ORDEM_SERVICO->value}}">Ordem de Serviço
                </flux:select.option>
                <flux:select.option value="{{\App\Enums\TipoOrdemServico::ORCAMENTO->value}}">Orçamento
                </flux:select.option>


            </flux:select>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
            <flux:date-picker wire:model="date">

                <x-slot name="trigger">
                    <flux:date-picker.input label="Data de Abertura"
                                            wire:model="form.dataAbertura"
                                            name="dataAbertura"
                    />
                </x-slot>

            </flux:date-picker>

            <flux:select label="Cliente*" variant="listbox" searchable
                         wire:model="form.idCliente"
                         placeholder="Selecione"
                         name="idCliente"
            >

                @foreach($this->form->clientes as $cliente)

                    <flux:select.option
                        value="{{$cliente->idCliente}}">{{$cliente->pessoa->nomeFantasia ?? $cliente->pessoa->nomeRazaoSocial}}</flux:select.option>
                @endforeach


            </flux:select>

            <flux:select label="Status" variant="listbox" searchable
                         wire:model="form.status"
                         placeholder="Selecione"
                         name="status">
                <flux:select.option value="{{\App\Enums\StatusOrdemServico::PENDENTE->value}}">Pendente
                </flux:select.option>
                <flux:select.option value="{{\App\Enums\StatusOrdemServico::EM_ANDAMENTO->value}}">Em Andamento
                </flux:select.option>
                <flux:select.option value="{{\App\Enums\StatusOrdemServico::FINALIZADO->value}}">Finalizado
                </flux:select.option>
            </flux:select>


        </div>

        <hr class="w-full h-px bg-accent my-4">

        <flux:accordion>
            <flux:accordion.item :expanded="true">
                <flux:accordion.heading>Materiais</flux:accordion.heading>
                <flux:accordion.content>

                    <flux:card class="space-y-6 mt-4 mb-4">
                        <flux:button variant="primary" wire:click="$dispatchTo(
                                                                                                '{{ \App\Livewire\Forms\AdicionarMateriaisForm::PATH_COMPONENT_FORM_SELECIONAR_MATERIAL }}',
                                                                                                '{{ \App\Livewire\Forms\AdicionarMateriaisForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_MATERIAL }}',
                                                                                                {
                                                                                                    modalName: '{{ \App\Livewire\Forms\AdicionarMateriaisForm::MODAL_NAME_SELECIONAR_MATERIAL }}'
                                                                                                }
                                                                                            )">+ Adicionar Material
                        </flux:button>

                        @if(count($form->materiais)>0)
                            <flux:table class="">
                                <flux:table.columns>

                                    <flux:table.column>Codigo</flux:table.column>
                                    <flux:table.column>Nome</flux:table.column>
                                    <flux:table.column>Quantidade</flux:table.column>
                                    <flux:table.column>Valor Unitario</flux:table.column>
                                    <flux:table.column>Valor Total</flux:table.column>


                                </flux:table.columns>

                                <flux:table.rows>
                                    @foreach($form->materiais as $material)


                                        <flux:table.row>
                                            <flux:table.cell>{{$material['codigo']}}</flux:table.cell>
                                            <flux:table.cell>{{$material['nome']}}</flux:table.cell>
                                            <flux:table.cell>{{$material['quantidade']}}</flux:table.cell>
                                            <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($material['valorUnitario'])}}</flux:table.cell>
                                            <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($material['valorTotal'])}}</flux:table.cell>
                                        </flux:table.row>
                                        @php
                                            $this->valorMateriais += $material['valorTotal'];
                                        @endphp
                                    @endforeach
                                </flux:table.rows>


                            </flux:table>

                        @endif

                        <flux:separator></flux:separator>
                        <div>

                            <flux:heading class="mt-4">Valor total de materiais</flux:heading>
                            <flux:heading size="xl"
                                          class=" inline-block mt-2 strong bg-neutral-100 dark:bg-neutral-800 px-4 py-1 rounded">
                                R$ {{ Helper::formatarValorMonetarioPtBr($this->valorMateriais)}}</flux:heading>
                        </div>
                    </flux:card>
                </flux:accordion.content>
            </flux:accordion.item>


            <flux:accordion.item :expanded="true">

                <flux:accordion.heading>Serviços</flux:accordion.heading>
                <flux:accordion.content>

                    <flux:card class="space-y-6 mt-4 mb-4">
                        <flux:button variant="primary" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\AdicionarServicosForm::PATH_COMPONENT_FORM_SELECIONAR_SERVICO }}',
                                                                                    '{{ \App\Livewire\Forms\AdicionarServicosForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_SERVICO }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\AdicionarServicosForm::MODAL_NAME_SELECIONAR_SERVICO }}'
                                                                                    }
                                                                                )">+ Adicionar Serviço
                        </flux:button>

                        @if(count($form->servicos)>0)
                            <flux:table class="">
                                <flux:table.columns>

                                    <flux:table.column>Codigo</flux:table.column>
                                    <flux:table.column>Nome</flux:table.column>
                                    <flux:table.column>Quantidade</flux:table.column>
                                    <flux:table.column>Valor Unitario</flux:table.column>
                                    <flux:table.column>Valor Total</flux:table.column>


                                </flux:table.columns>

                                <flux:table.rows>
                                    @foreach($form->servicos as $servico)

                                        <flux:table.row>
                                            <flux:table.cell>{{$servico['codigo']}}</flux:table.cell>
                                            <flux:table.cell>{{$servico['nome']}}</flux:table.cell>
                                            <flux:table.cell>{{$servico['quantidade']}}</flux:table.cell>
                                            <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($servico['valor_unitario'])}}</flux:table.cell>
                                            <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($servico['valor_total'])}}</flux:table.cell>
                                        </flux:table.row>
                                        @php
                                            $this->valorServicos += $servico['valor_total'];
                                        @endphp
                                    @endforeach
                                </flux:table.rows>


                            </flux:table>

                        @endif
                        <flux:separator></flux:separator>
                        <div>

                            <flux:heading class="mt-4">Valor total de servicos</flux:heading>
                            <flux:heading size="xl"
                                          class=" inline-block mt-2 strong bg-neutral-100 dark:bg-neutral-800 px-4 py-1 rounded">
                                R$ {{ Helper::formatarValorMonetarioPtBr($this->valorServicos)}}</flux:heading>
                        </div>

                    </flux:card>
                </flux:accordion.content>
            </flux:accordion.item>
        </flux:accordion>

        <hr class="w-full h-px bg-accent">


        <div class="my-4">

            <flux:heading class="mt-4">Valor total ordem de serviço</flux:heading>
            <flux:heading size="xl"
                          class=" inline-block strong bg-neutral-100 dark:bg-neutral-800 px-4 py-1 rounded">
                R$ {{ Helper::formatarValorMonetarioPtBr($form->valorTotal = $this->valorServicos + $this->valorMateriais)}}
            </flux:heading>
        </div>


        <flux:button type="submit" variant="primary" class="mt-2">Salvar</flux:button>
    </form>
</div>

