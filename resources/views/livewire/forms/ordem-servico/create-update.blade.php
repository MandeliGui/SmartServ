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
    public mixed $id;


    public function save(): void
    {


        if ($this->persistence == Persistence::UPDATE) {

            $ordemServico = $this->form->update();

            $this->dispatch(OrdemServicoForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, ordemServico: $ordemServico);

            $this->redirect(route('ordem-servico'), navigate: true);

        } else {

            try {

                $ordemServico = $this->form->create();


                $this->dispatch(OrdemServicoForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, ordemServico: $ordemServico);
                $this->redirect(route('ordem-servico'), navigate: true);
            } catch (Throwable $e) {
                throw $e;
            }

        }

    }

    #[On(AdicionarMateriaisForm::EVENT_PERSISTED)]
    public function atualizarMateriais($materiais, $persistence)
    {

        if ($persistence === Persistence::UPDATE->value) {
            $this->form->editarMaterial($materiais);

        } else {
            $this->form->materiais = array_merge($this->form->materiais ?? [], $materiais);

        }

    }

    #[On(AdicionarServicosForm::EVENT_PERSISTED)]
    public function atualizarServicos($servicos, $persistence)
    {
        if ($persistence === Persistence::UPDATE->value) {
            $this->form->editarServico($servicos);
        } else {

            $this->form->servicos = array_merge($this->form->servicos ?? [], $servicos);
        }
    }

    public
    function removeMaterial($index)
    {
        if ($this->form->materiais[$index]['id']) {
            $this->form->removeMaterial($this->form->materiais[$index]['id']);
        } else {
            unset($this->form->materiais[$index]);
            $this->form->materiais = array_values($this->form->materiais);
            Flux::toast('Material removido com sucesso!', variant: 'success');

        }
    }

    public
    function removeServico($index)
    {

        if ($this->form->servicos[$index]['id']) {

            $this->form->removeServico($this->form->servicos[$index]['id']);
        } else {
            unset($this->form->servicos[$index]);
            $this->form->servicos = array_values($this->form->servicos);
            Flux::toast('Serviço removido com sucesso!', variant: 'success');
        }
    }

    public function finalizarOrdemServico()
    {

        $this->form->id = $this->id;
        $id             = $this->form->id;

        if ($this->form->status === \App\Enums\StatusOrdemServico::FINALIZADO->value) {
            Flux::toast('Ordem de serviço já está finalizada!', variant: 'warning');
            return;
        }

        $ordemServico = $this->form->finalizarOrdemServico();
        Flux::toast('Ordem de serviço finalizada com sucesso!', variant: 'success');
        $this->mount();
        $this->id = $id;

    }

    public function cancelarOrdemServico()
    {
        $this->form->id = $this->id;
        $id             = $this->form->id;


        if ($this->form->status === \App\Enums\StatusOrdemServico::CANCELADO->value) {
            Flux::toast('Ordem de serviço já está cancelada!', variant: 'warning');
            return;
        }

        $ordemServico = $this->form->cancelarOrdemServico();
        Flux::toast('Ordem de serviço cancelada com sucesso!', variant: 'success');
        $this->mount();
        $this->id = $id;


    }

    public function reabrirOrdemServico()
    {
        $this->form->id = $this->id;
        $id             = $this->form->id;

        if ($this->form->status === \App\Enums\StatusOrdemServico::PENDENTE->value ||
            $this->form->status === \App\Enums\StatusOrdemServico::EM_ANDAMENTO->value) {
            Flux::toast('Ordem de serviço já está em andamento!', variant: 'warning');
            return;
        }

        $ordemServico = $this->form->reabrirOrdemServico();
        Flux::toast('Ordem de serviço reaberta com sucesso!', variant: 'success');
        $this->mount();
        $this->id = $id;


    }

    public
    function mount()
    {


        $this->id       = request()->route('id') ?? null;
        $this->form->id = request()->route('id') ?? null;


        $this->form->dataAbertura = now()->format('Y-m-d');
        $this->form->clientes     = \App\Models\ClienteModel::query()->get();

        if ($this->id) {

            $this->persistence = Persistence::UPDATE;
            $this->form->setOrdemServico($this->id);
        }

        if ($this->persistence === Persistence::CREATE) {
            $this->form->codigo = (string)(OrdemServicoModel::latest()->first() ? (int)OrdemServicoModel::latest()->first()->codigo + 1 : 1);

        }
    }


    private function finalizadaOuCancelada()
    {

        if ($this->form->status === \App\Enums\StatusOrdemServico::FINALIZADO->value ||
            $this->form->status === \App\Enums\StatusOrdemServico::CANCELADO->value) {
            return true;
        } else {
            return false;
        }
    }


};
?>

<div x-data>


    <flux:tab.group>
        <flux:tabs wire:model="tab">
            <flux:tab name="dados">Dados</flux:tab>
            <flux:tab name="pagamento" :disabled="$form->status !== \App\Enums\StatusOrdemServico::FINALIZADO->value">
                Pagamento
            </flux:tab>
        </flux:tabs>
        <flux:tab.panel name="dados">


            <form>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
                    <div>

                        <flux:heading class="">Nº Ordem de Serviço</flux:heading>
                        <flux:heading size="xl"
                                      class=" inline-block mt-2 text-accent-content strong bg-neutral-100 dark:bg-neutral-800 px-4 py-1 rounded">{{$form->codigo}}</flux:heading>
                    </div>

                    <flux:select label="Tipo"
                                 wire:model="form.tipo"
                                 placeholder="Selecione"
                                 variant="listbox"
                                 :disabled="$this->finalizadaOuCancelada()"
                                 name="tipo"
                    >

                        <flux:select.option value="{{\App\Enums\TipoOrdemServico::ORDEM_SERVICO->value}}">Ordem de
                            Serviço
                        </flux:select.option>
                        <flux:select.option value="{{\App\Enums\TipoOrdemServico::ORCAMENTO->value}}">Orçamento
                        </flux:select.option>


                    </flux:select>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
                    <flux:date-picker wire:model="date" :disabled="$this->finalizadaOuCancelada()">

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
                                 :disabled="$this->finalizadaOuCancelada()"
                                 name="idCliente"
                    >


                        @foreach($form->clientes as $cliente)

                            <flux:select.option value="{{$cliente->idCliente}}">
                                {{$cliente->pessoa->nomeFantasia ?: $cliente->pessoa->nomeRazaoSocial}}
                            </flux:select.option>

                        @endforeach


                    </flux:select>

                    <flux:select label="Status" variant="listbox" searchable
                                 wire:model="form.status"
                                 :disabled="$this->finalizadaOuCancelada()"
                                 placeholder="Selecione"
                                 name="status">
                        @if($form->status === \App\Enums\StatusOrdemServico::FINALIZADO->value)
                            <flux:select.option value="{{\App\Enums\StatusOrdemServico::FINALIZADO->value}}">
                                Finalizado
                            </flux:select.option>

                        @elseif($form->status === \App\Enums\StatusOrdemServico::CANCELADO->value)
                            <flux:select.option value="{{\App\Enums\StatusOrdemServico::CANCELADO->value}}">
                                Cancelado
                            </flux:select.option>
                        @else
                            <flux:select.option value="{{\App\Enums\StatusOrdemServico::PENDENTE->value}}">Pendente
                            </flux:select.option>
                            <flux:select.option value="{{\App\Enums\StatusOrdemServico::EM_ANDAMENTO->value}}">Em
                                Andamento
                            </flux:select.option>
                        @endif

                    </flux:select>


                </div>

                <hr class="w-full h-px bg-accent my-4">

                <flux:accordion>
                    <flux:accordion.item :expanded="true">

                        <flux:accordion.heading>
                            <div class="flex justify-between items-center">
                                <div>

                                    Materiais
                                </div>
                                <div>
                                    @php
                                        $valorMateriais = collect($form->materiais)->sum('valorTotal');
                                    @endphp

                                    <div class="flex ">
                                        <flux:heading class="mt-4 me-4">Valor total de materiais</flux:heading>
                                        <flux:heading size="xl"
                                                      class="inline-block mt-2 strong bg-neutral-100 dark:bg-neutral-800 px-4 py-1 rounded">
                                            R$ {{ Helper::formatarValorMonetarioPtBr($valorMateriais)}}</flux:heading>
                                    </div>
                                </div>
                            </div>
                        </flux:accordion.heading>

                        <flux:accordion.content>

                            <flux:card class="space-y-6 mt-4 mb-4">

                                <div class="flex justify-between items-center">
                                    @if(!$this->finalizadaOuCancelada())

                                        <flux:button variant="primary" wire:click="$dispatchTo(
                                                                                                    '{{ \App\Livewire\Forms\AdicionarMateriaisForm::PATH_COMPONENT_FORM_SELECIONAR_MATERIAL }}',
                                                                                                    '{{ \App\Livewire\Forms\AdicionarMateriaisForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_MATERIAL }}',
                                                                                                    {
                                                                                                        modalName: '{{ \App\Livewire\Forms\AdicionarMateriaisForm::MODAL_NAME_SELECIONAR_MATERIAL }}'
                                                                                                    }
                                                                                                )">+ Adicionar Material
                                        </flux:button>
                                    @endif


                                </div>

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

                                                <flux:table.row
                                                    class="hover:bg-neutral-100 dark:hover:bg-neutral-700">
                                                    <flux:table.cell>{{$material['codigo']}}</flux:table.cell>
                                                    <flux:table.cell>{{$material['nome']}}</flux:table.cell>
                                                    <flux:table.cell>{{$material['quantidade']}}</flux:table.cell>
                                                    <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($material['valorUnitario'])}}</flux:table.cell>
                                                    <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($material['valorTotal'])}}</flux:table.cell>

                                                    @if(!is_null($material['id']))
                                                        <flux:table.cell>
                                                            <flux:button wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\AdicionarMateriaisForm::PATH_COMPONENT_FORM_SELECIONAR_MATERIAL }}',
                                                                                    '{{ \App\Livewire\Forms\AdicionarMateriaisForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_MATERIAL }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\AdicionarMateriaisForm::MODAL_NAME_SELECIONAR_MATERIAL }}',
                                                                                        idMaterial: {{$material['id']}}
                                                                                    }
                                                                                )"
                                                                         variant="primary" size="xs">
                                                                <flux:icon icon="pencil" variant="micro"/>
                                                            </flux:button>

                                                            <flux:button
                                                                wire:click="removeMaterial({{$loop->index}})"
                                                                variant="danger" size="xs">
                                                                <flux:icon icon="trash" variant="micro"/>
                                                            </flux:button>
                                                        </flux:table.cell>
                                                    @else
                                                        <flux:table.cell>
                                                            <flux:button
                                                                wire:click="removeMaterial({{$loop->index}})"
                                                                variant="danger" size="xs">
                                                                <flux:icon variant="micro" icon="trash"/>
                                                            </flux:button>
                                                        </flux:table.cell>
                                                    @endif
                                                </flux:table.row>
                                            @endforeach
                                        </flux:table.rows>


                                    </flux:table>

                                @else
                                    <div
                                        class="w-full text-center py-3 rounded-lg border-2 border-accent">
                                        <p class="font-semibold text-accent">
                                            Nenhum registro encontrado.
                                        </p>
                                    </div>
                                @endif

                                {{--                        <flux:separator></flux:separator>--}}

                            </flux:card>
                        </flux:accordion.content>
                    </flux:accordion.item>


                    <flux:accordion.item :expanded="true">

                        <flux:accordion.heading >
                            <div class="flex justify-between items-center">



                                    <div>
                                        Serviços
                                    </div>
                                    <div>
                                        @php
                                            $valorServicos = collect($form->servicos)->sum('valorTotal');
                                        @endphp

                                        <div class="flex">

                                            <flux:heading class="mt-4 me-4">Valor total de servicos</flux:heading>
                                            <flux:heading size="xl"
                                                          class=" inline-block mt-2 strong bg-neutral-100 dark:bg-neutral-800 px-4 py-1 rounded">
                                                R$ {{ Helper::formatarValorMonetarioPtBr($valorServicos)}}</flux:heading>
                                        </div>
                                    </div>

                            </div>
                        </flux:accordion.heading>
                        <flux:accordion.content>

                            <flux:card class="space-y-6 mt-4 mb-4">
                                <div class="flex justify-between items-center">
                                    @if(!$this->finalizadaOuCancelada())

                                        <flux:button variant="primary" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\AdicionarServicosForm::PATH_COMPONENT_FORM_SELECIONAR_SERVICO }}',
                                                                                    '{{ \App\Livewire\Forms\AdicionarServicosForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_SERVICO }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\AdicionarServicosForm::MODAL_NAME_SELECIONAR_SERVICO }}'
                                                                                    }
                                                                                )">+ Adicionar Serviço
                                        </flux:button>
                                    @endif


                                </div>

                                @if(count($form->servicos)>0)
                                    <flux:table class="">
                                        <flux:table.columns>

                                            <flux:table.column>Codigo</flux:table.column>
                                            <flux:table.column>Nome</flux:table.column>
                                            <flux:table.column>Quantidade</flux:table.column>
                                            <flux:table.column>Valor Unitario</flux:table.column>
                                            <flux:table.column>Valor Total</flux:table.column>
                                            <flux:table.column></flux:table.column>


                                        </flux:table.columns>

                                        <flux:table.rows>
                                            @foreach($form->servicos as $servico)

                                                <flux:table.row
                                                    class="hover:bg-neutral-100 dark:hover:bg-neutral-700">
                                                    <flux:table.cell>{{$servico['codigo']}}</flux:table.cell>
                                                    <flux:table.cell>{{$servico['nome']}}</flux:table.cell>
                                                    <flux:table.cell>{{$servico['quantidade']}}</flux:table.cell>
                                                    <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($servico['valorUnitario'])}}</flux:table.cell>
                                                    <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($servico['valorTotal'])}}</flux:table.cell>
                                                    @if(!is_null($servico['id']))
                                                        <flux:table.cell>
                                                            <div>

                                                                <flux:button wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\AdicionarServicosForm::PATH_COMPONENT_FORM_SELECIONAR_SERVICO }}',
                                                                                    '{{ \App\Livewire\Forms\AdicionarServicosForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_SERVICO }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\AdicionarServicosForm::MODAL_NAME_SELECIONAR_SERVICO }}',
                                                                                        idServico: {{$servico['id']}}
                                                                                    }
                                                                                )"
                                                                             variant="primary" size="xs">
                                                                    <flux:icon icon="pencil" variant="micro"/>
                                                                </flux:button>

                                                                <flux:button
                                                                    wire:click="removeServico({{$loop->index}})"
                                                                    variant="danger" size="xs">
                                                                    <flux:icon icon="trash" variant="micro"/>
                                                                </flux:button>
                                                            </div>
                                                        </flux:table.cell>
                                                    @else
                                                        <flux:table.cell>
                                                            <flux:button
                                                                wire:click="removeServico({{$loop->index}})"
                                                                variant="danger" size="xs">
                                                                <flux:icon icon="trash" variant="micro"/>
                                                            </flux:button>
                                                        </flux:table.cell>
                                                    @endif
                                                </flux:table.row>
                                            @endforeach
                                        </flux:table.rows>


                                    </flux:table>
                                @else
                                    <div
                                        class="w-full text-center py-3 rounded-lg border-2 border-accent">
                                        <p class="font-semibold text-accent">
                                            Nenhum registro encontrado.
                                        </p>
                                    </div>

                                @endif


                            </flux:card>
                        </flux:accordion.content>
                    </flux:accordion.item>
                </flux:accordion>

                <flux:separator class="mt-4" />


                <div class="flex flex-col justify-end items-end  my-4">

                    <flux:heading class="mt-4">Valor total ordem de serviço</flux:heading>
                    <flux:heading size="xl"
                                  class=" inline-block strong bg-neutral-100 dark:bg-neutral-800 px-4 py-1 rounded">
                        R$ {{ Helper::formatarValorMonetarioPtBr($form->valorTotal = $valorServicos + $valorMateriais)}}
                    </flux:heading>
                </div>

                <div class="flex justify-between items-center">
                    @if(!$this->finalizadaOuCancelada())
                        <div>

                            <flux:button wire:click.prevent="save" type="submit" variant="primary" class="mt-2"
                                         icon:trailing="save">
                                @if($persistence === Persistence::UPDATE)
                                    Salvar
                                @else
                                    Criar
                                @endif
                            </flux:button>

                            @if(isset($this->id))
                                <flux:button type="submit" wire:click.prevent="finalizarOrdemServico" variant="success"
                                             icon:trailing="check">Finalizar
                                </flux:button>
                            @endif
                        </div>
                        @if(isset($this->id))
                            <flux:button variant="danger" wire:click.prevent="cancelarOrdemServico" class="mt-2"
                                         icon:trailing="x-mark">Cancelar
                            </flux:button>
                        @endif
                    @else
                        <div>
                            <flux:button wire:click.prevent="reabrirOrdemServico" variant="info" class="mt-2"
                                         icon:trailing="arrow-path">
                                Reabrir Comanda
                            </flux:button>
                        </div>
                    @endif
                </div>
            </form>

        </flux:tab.panel>
        <flux:tab.panel name="pagamento"></flux:tab.panel>
    </flux:tab.group>

</div>

