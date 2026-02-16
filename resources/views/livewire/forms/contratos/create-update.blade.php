<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\AdicionarMateriaisForm;
use App\Livewire\Forms\AdicionarServicosForm;
use App\Livewire\Forms\ContratoForm;
use App\Models\ContratosModel;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public ContratoForm $form;
    public ?Persistence $persistence = Persistence::CREATE;

    public float $valorMateriais = 0;
    public float $valorServicos  = 0;
    public mixed $id             = null;


    public function save(): void
    {


        if ($this->persistence == Persistence::UPDATE) {

            $contrato = $this->form->update();

            $this->dispatch(ContratoForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, contrato: $contrato);

            $this->redirect(route('contratos'), navigate: true);

        } else {

            try {

                $contrato = $this->form->create();


                $this->dispatch(ContratoForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, contrato: $contrato);
                $this->redirect(route('contratos'), navigate: true);
            } catch (Throwable $e) {
                throw $e;
            }

        }

    }

    #[On(AdicionarMateriaisForm::EVENT_PERSISTED_CONTRATO)]
    public function atualizarMateriais($materiais, $persistence)
    {
        if ($persistence === Persistence::UPDATE->value) {
            $this->form->editarMaterial($materiais);

        } else {
            $this->form->materiais = array_merge($this->form->materiais ?? [], $materiais);

        }

    }

    #[On(AdicionarServicosForm::EVENT_PERSISTED_CONTRATO)]
    public function atualizarServicos($servicos, $persistence)
    {
        if ($persistence === Persistence::UPDATE->value) {
            $this->form->editarServico($servicos);
        } else {

            $this->form->servicos = array_merge($this->form->servicos ?? [], $servicos);
        }
    }

    public function removeMaterial($index)
    {
        if ($this->form->materiais[$index]['id']) {
            $this->form->removeMaterial($this->form->materiais[$index]['id']);
        } else {
            unset($this->form->materiais[$index]);
            $this->form->materiais = array_values($this->form->materiais);
            Flux::toast('Material removido com sucesso!', variant: 'success');

        }
    }

    public function removeServico($index)
    {

        if ($this->form->servicos[$index]['id']) {

            $this->form->removeServico($this->form->servicos[$index]['id']);
        } else {
            unset($this->form->servicos[$index]);
            $this->form->servicos = array_values($this->form->servicos);
            Flux::toast('Serviço removido com sucesso!', variant: 'success');
        }
    }


    public function mount()
    {


        $this->id       = request()->route('id') ?? null;
        $this->form->id = request()->route('id') ?? null;
        $this->form->dataInicioContrato = \Carbon\Carbon::now()->format('Y-m-d');

        $this->form->clientes = \App\Models\ClienteModel::query()->get();

        if ($this->id) {

            $this->persistence = Persistence::UPDATE;
            $this->form->setContrato($this->id);
        }
    }


};
?>


<div x-data>
    <form>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-4">

            <div class="col-span-2">

                <flux:select label="Cliente*" variant="listbox"
                             :searchable="true"
                             wire:model="form.idCliente"
                             placeholder="Selecione"
                             name="idCliente"
                >


                    @foreach($form->clientes as $cliente)

                        <flux:select.option value="{{$cliente->idCliente}}">
                            {{$cliente->pessoa->nomeFantasia ?: $cliente->pessoa->nomeRazaoSocial}}
                        </flux:select.option>

                    @endforeach
                </flux:select>

            </div>

            <flux:select label="Periodicidade*" variant="listbox"
                         wire:model="form.periodicidade"
                         placeholder="Selecione"
                         name="periodicidade"
            >

                @foreach(\App\Enums\Periodicidade::cases() as $item)
                    <flux:select.option value="{{$item->value}}">
                        {{$item->name}}
                    </flux:select.option>
                @endforeach

            </flux:select>

            <flux:date-picker
                wire:model="form.dataInicioContrato">

                <x-slot name="trigger">
                    <flux:date-picker.input
                        label='Data Inicio do Contrato*'

                        name="dataInicioContrato"


                    />
                </x-slot>

            </flux:date-picker>
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

                            <flux:button variant="primary" wire:click="$dispatchTo(
                                                                                                    '{{ \App\Livewire\Forms\AdicionarMateriaisForm::PATH_COMPONENT_FORM_SELECIONAR_MATERIAL_CONTRATO }}',
                                                                                                    '{{ \App\Livewire\Forms\AdicionarMateriaisForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_MATERIAL }}',
                                                                                                    {
                                                                                                        modalName: '{{ \App\Livewire\Forms\AdicionarMateriaisForm::MODAL_NAME_SELECIONAR_MATERIAL }}'
                                                                                                    }
                                                                                                )">+ Adicionar Material
                            </flux:button>


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
                                                                                    '{{ \App\Livewire\Forms\AdicionarMateriaisForm::PATH_COMPONENT_FORM_SELECIONAR_MATERIAL_CONTRATO }}',
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
                                    @if(!empty($material['descricao']))
                                        {{-- Linha da descrição --}}
                                        <flux:table.row>
                                            <flux:table.cell colspan="6">
                                                <strong>Observação:</strong>
                                                {{ $material['descricao']}}
                                            </flux:table.cell>
                                        </flux:table.row>
                                    @endif
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

                <flux:accordion.heading>
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

                            <flux:button variant="primary" wire:click="$dispatchTo(
                                                                                    '{{ \App\Livewire\Forms\AdicionarServicosForm::PATH_COMPONENT_FORM_SELECIONAR_SERVICO_CONTRATO }}',
                                                                                    '{{ \App\Livewire\Forms\AdicionarServicosForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_SERVICO }}',
                                                                                    {
                                                                                        modalName: '{{ \App\Livewire\Forms\AdicionarServicosForm::MODAL_NAME_SELECIONAR_SERVICO }}'
                                                                                    }
                                                                                )">+ Adicionar Serviço
                            </flux:button>


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
                                                                                    '{{ \App\Livewire\Forms\AdicionarServicosForm::PATH_COMPONENT_FORM_SELECIONAR_SERVICO_CONTRATO }}',
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
                                @if(!empty($servico['descricao']))
                                    {{-- Linha da descrição --}}
                                    <flux:table.row>
                                        <flux:table.cell colspan="6">
                                            <strong>Observação:</strong>
                                            {{ $servico['descricao']}}
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endif


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

        <flux:separator class="mt-4"/>


        <div class="flex flex-col justify-end items-end  my-4">

            <flux:heading class="mt-4">Valor total ordem de serviço</flux:heading>
            <flux:heading size="xl"
                          class=" inline-block strong bg-neutral-100 dark:bg-neutral-800 px-4 py-1 rounded">
                R$ {{ Helper::formatarValorMonetarioPtBr($form->valorTotal = $valorServicos + $valorMateriais)}}
            </flux:heading>
        </div>

        <flux:button wire:click.prevent="save" type="submit" variant="primary" class="mt-2"
                     icon:trailing="save">
            @if($persistence === Persistence::UPDATE)
                Salvar Contrato
            @else
                Criar Contrato
            @endif
        </flux:button>


    </form>

</div>

