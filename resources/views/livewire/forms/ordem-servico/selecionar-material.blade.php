<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\AdicionarMateriaisForm;
use App\Livewire\Forms\OrdemServicoForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public AdicionarMateriaisForm $form;
    public OrdemServicoForm       $ordemServicoForm;
    public ?Persistence           $persistence = Persistence::CREATE;

    public array $materiaisAdicionados = [];

    public function addMateriais(): void
    {


        Validator::make($this->form->only('id_material', 'quantidade', 'valorUnitario'),
            rules: [
                'id_material'   => 'required',
                'quantidade'    => 'required|numeric',
                'descricao'     => 'nullable',
                'valorUnitario' => 'required',
            ],
            messages: [
                'id_material.required'   => 'O campo materiais é obrigatório.',
                'quantidade.required'    => 'O campo quantidade é obrigatório.',
                'quantidade.numeric'     => 'O campo quantidade deve ser numérico.',
                'valorUnitario.required' => 'O campo valor é obrigatório.',
            ])->validate();

        $material = $this->form->getMaterialById($this->form->id_material);


        $this->materiaisAdicionados[] = [
            'id'            => null,
            'idMaterial'    => $this->form->id_material,
            'codigo'        => $material->codigo,
            'quantidade'    => $this->form->quantidade,
            'descricao'     => $this->form->descricao,
            'nome'          => $material->nome,
            'valorUnitario' => $valorUnitario = Helper::formatarDecimalDb($this->form->valorUnitario),
            'valorTotal'    => $valorUnitario * $this->form->quantidade,

        ];


        $this->form->id_material   = null;
        $this->form->quantidade    = null;
        $this->form->valorUnitario = null;


    }

    public function editarMateriais()
    {
        $this->form->valorTotal = $this->form->valorUnitario * $this->form->quantidade;

        $this->dispatch(AdicionarMateriaisForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, materiais: $this->form->all());
        \Flux::modal(AdicionarMateriaisForm::MODAL_NAME_SELECIONAR_MATERIAL)->close();

    }

    public function atualizarValorUnitario()
    {
        if ($this->form->id_material) {
            $material = \App\Models\MaterialModel::find($this->form->id_material);

            $this->form->valorUnitario = Helper::formatarValorMonetarioPtBr($material->valor);
        } else {
            $this->form->valorUnitario = null;
        }
    }

    public function save(): void
    {

        if (!isset($this->materiaisAdicionados['id'])) {
            $this->ordemServicoForm->materiais = $this->materiaisAdicionados;

            $this->dispatch(AdicionarMateriaisForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, materiais: $this->ordemServicoForm->materiais);

            \Flux::modal(AdicionarMateriaisForm::MODAL_NAME_SELECIONAR_MATERIAL)->close();

            $this->materiaisAdicionados = [];
        } else {

            $this->ordemServicoForm->materiais = $this->materiaisAdicionados;


            $this->dispatch(AdicionarMateriaisForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, materiais: $this->ordemServicoForm->materiais);

            \Flux::modal(AdicionarMateriaisForm::MODAL_NAME_SELECIONAR_MATERIAL)->close();
        }

    }

    public function mount()
    {
        $this->ordemServicoForm->id = request()->route('id') ?? null;

    }

    public function removeMaterial(int $index)
    {
        if (isset($this->materiaisAdicionados[$index])) {
            unset($this->materiaisAdicionados[$index]);
            $this->materiaisAdicionados = array_values($this->materiaisAdicionados); // Reindexa o array
        }
    }

    #[On(AdicionarMateriaisForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_MATERIAL)]
    public function openModalSelecionarMaterial(string $modalName, mixed $idMaterial = null): void
    {

        if ($idMaterial) {
            $this->form->idMaterialSelecionado = $idMaterial;

            $material = \App\Models\OrdemServicoModel::query()
                                                     ->whereHas('materiais', function ($query) use ($idMaterial) {
                                                         $query->where('tb_ordem_servico_material.id', $idMaterial);
                                                     })
                                                     ->first();

            if ($material) {
                $pivotData = $material->materiais()
                                      ->wherePivot('id', $idMaterial)
                                      ->first();

                $this->form->id_material   = $pivotData->pivot->idMaterial;
                $this->form->quantidade    = $pivotData->pivot->quantidade;
                $this->form->valorUnitario = $pivotData->pivot->valorUnitario;
                $this->form->descricao     = $pivotData->pivot->descricao;


            }
            $this->persistence = Persistence::UPDATE;
        } else {

            $this->form->materiais = \App\Models\MaterialModel::query()->where('removido', false)->get();


            $this->persistence = Persistence::CREATE;
        }


        Flux::modal($modalName)->show();
    }

    #[On(AdicionarMateriaisForm::EVENT_PERSISTED)]
    public function with()
    {
        $materiais = \App\Models\MaterialModel::query()
                                              ->get();

        return [
            'materiais' => $materiais,
        ];
    }


};
?>

<div x-data>
    <form wire:submit.prevent="save">
        <flux:text>Selecione o Material</flux:text>

        <hr class="w-full h-px bg-accent mt-2">

        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 my-4">
            <flux:select label="Material*" variant="listbox" searchable
                         wire:model="form.id_material"
                         placeholder="Selecione"
                         name="material.codigo"
                         wire:change="atualizarValorUnitario"
                         :disabled="$persistence === Persistence::UPDATE"
            >

                <flux:select.option value="">Selecione</flux:select.option>
                @foreach($materiais as $material)

                    <flux:select.option
                        value="{{$material->id}}">{{$material->nome}}</flux:select.option>
                @endforeach


            </flux:select>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-4">

            <flux:input label="Quantidade*" placeholder="Digite a quantidade"
                        wire:model="form.quantidade"
                        name="quantidade"
            />
            <flux:input label="Valor Unitario*" placeholder="Digite a quantidade"
                        wire:model="form.valorUnitario"
                        name="valorUnitario"
            />
        </div>

        <flux:textarea
            label="Descrição"
            placeholder="Digite a descrição"
            wire:model="form.descricao"
            name="descricao"
        />

        @if($persistence === Persistence::CREATE)

            <flux:button wire:click="addMateriais" variant="primary" class="mt-2">Adicionar</flux:button>
        @else
            <flux:button wire:click="editarMateriais" variant="primary" class="mt-2">Atualizar</flux:button>
        @endif
    </form>


    @if(count($materiaisAdicionados) >0 )

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
                @foreach($materiaisAdicionados as $material)
                    {{-- Linha principal --}}
                    <flux:table.row>
                        <flux:table.cell>{{ $material['codigo'] }}</flux:table.cell>
                        <flux:table.cell>{{ $material['nome'] }}</flux:table.cell>
                        <flux:table.cell>{{ $material['quantidade'] }}</flux:table.cell>
                        <flux:table.cell>
                            R$ {{ Helper::formatarValorMonetarioPtBr($material['valorUnitario']) }}
                        </flux:table.cell>
                        <flux:table.cell>
                            R$ {{ Helper::formatarValorMonetarioPtBr($material['valorTotal']) }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button
                                wire:click="removeMaterial({{ $loop->index }})"
                                variant="danger"
                            >
                                <flux:icon icon="trash"/>
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>

                    @if(!empty($material['descricao']))

                        {{-- Linha da descrição --}}
                        <flux:table.row
                        >
                            <flux:table.cell colspan="6">
                                <strong>Observação:</strong>
                                {{ $material['descricao'] }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endif
                @endforeach
            </flux:table.rows>


        </flux:table>

        <div class="flex justify-end mt-4">
            <flux:button wire:click="save()" variant="primary" class="mt-2">Salvar</flux:button>
        </div>
    @endif

</div>

