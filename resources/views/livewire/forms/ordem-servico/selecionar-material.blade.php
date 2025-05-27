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


        Validator::make($this->form->only('id_material', 'quantidade'),
            rules: [
                'id_material' => 'required',
                'quantidade'  => 'required|numeric',
            ],
            messages: [
                'id_material.required' => 'O campo materiais é obrigatório.',
                'quantidade.required'  => 'O campo quantidade é obrigatório.',
                'quantidade.numeric'   => 'O campo quantidade deve ser numérico.',
            ])->validate();

        $material = $this->form->getMaterialById($this->form->id_material);


        $this->materiaisAdicionados[] = [
            'idMaterial'    => $this->form->id_material,
            'codigo'        => $material->codigo,
            'quantidade'    => $this->form->quantidade,
            'nome'          => $material->nome,
            'valorUnitario' => $material->valor,
            'valorTotal'    => $material->valor * $this->form->quantidade,

        ];



        $this->form->id_material = null;
        $this->form->quantidade  = null;

    }

    public function save(): void
    {
        $this->ordemServicoForm->materiais = $this->materiaisAdicionados;

        $this->dispatch(AdicionarMateriaisForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, materiais: $this->ordemServicoForm->materiais);

        \Flux::modal(AdicionarMateriaisForm::MODAL_NAME_SELECIONAR_MATERIAL)->close();

        $this->materiaisAdicionados = [];

    }

    public function mount()
    {

    }

    #[On(AdicionarMateriaisForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_MATERIAL)]
    public function openModalSelecionarMaterial(string $modalName): void
    {


        $this->form->materiais = \App\Models\MaterialModel::query()->where('removido', false)->get();


        $this->persistence = Persistence::CREATE;

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
        </div>


        <flux:button wire:click="addMateriais" variant="primary" class="mt-2">Adicionar</flux:button>
    </form>


    @if(count($materiaisAdicionados) >0 )

        <flux:table class="">
            <flux:table.columns>

                <flux:table.column>Codigo</flux:table.column>
                <flux:table.column>Nome</flux:table.column>
                <flux:table.column>Quantidade</flux:table.column>
                <flux:table.column>Valor Unitario</flux:table.column>
                <flux:table.column>Valor Total</flux:table.column>


            </flux:table.columns>

            <flux:table.rows>
                @foreach($materiaisAdicionados as $material)
                    <flux:table.row>
                        <flux:table.cell>{{$material['codigo']}}</flux:table.cell>
                        <flux:table.cell>{{$material['nome']}}</flux:table.cell>
                        <flux:table.cell>{{$material['quantidade']}}</flux:table.cell>
                        <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($material['valorUnitario'])}}</flux:table.cell>
                        <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($material['valorTotal'])}}</flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>


        </flux:table>

        <div class="flex justify-end mt-4">
            <flux:button wire:click="save()" variant="primary" class="mt-2">Salvar</flux:button>
        </div>
    @endif

</div>

