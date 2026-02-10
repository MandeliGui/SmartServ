<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\AdicionarServicosForm;
use App\Livewire\Forms\OrdemServicoForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public AdicionarServicosForm $form;
    public OrdemServicoForm      $ordemServicoForm;
    public ?Persistence          $persistence = Persistence::CREATE;

    public array $servicosAdicionados = [];

    public function addServicos(): void
    {


        Validator::make($this->form->only('id_servico', 'quantidade', 'valorUnitario'),
            rules: [
                'id_servico'    => 'required',
                'quantidade'    => 'required|numeric',
                'valorUnitario' => 'required',
            ],
            messages: [
                'id_servico.required'    => 'O campo servico é obrigatório.',
                'quantidade.required'    => 'O campo quantidade é obrigatório.',
                'quantidade.numeric'     => 'O campo quantidade deve ser numérico.',
                'valorUnitario.required' => 'O campo valor é obrigatório.',
            ])->validate();

        $servico = $this->form->getServicoById($this->form->id_servico);


        $this->servicosAdicionados[] = [
            'id'            => null,
            'idServico'     => $this->form->id_servico,
            'codigo'        => $servico->codigo,
            'quantidade'    => $this->form->quantidade,
            'nome'          => $servico->nome,
            'valorUnitario' => $this->form->valorUnitario,
            'valorTotal'    => $this->form->valorUnitario * $this->form->quantidade,
        ];


        $this->form->id_servico    = null;
        $this->form->quantidade    = null;
        $this->form->valorUnitario = null;

    }

    public function editarServicos()
    {
        $this->form->valorTotal = $this->form->valorUnitario * $this->form->quantidade;

        $this->dispatch(AdicionarServicosForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, servicos: $this->form->all());
        \Flux::modal(AdicionarServicosForm::MODAL_NAME_SELECIONAR_SERVICO)->close();
    }

    public function atualizarValorUnitario()
    {
        if ($this->form->id_servico) {
            $servico                   = $this->form->getServicoById($this->form->id_servico);
            $this->form->valorUnitario = $servico->valor;
        } else {
            $this->form->valorUnitario = null;
        }
    }

    public function save(): void
    {
        if ($this->persistence === Persistence::CREATE) {
            $this->ordemServicoForm->servicos = $this->servicosAdicionados;

            $this->dispatch(AdicionarServicosForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, servicos: $this->ordemServicoForm->servicos);

            \Flux::modal(AdicionarServicosForm::MODAL_NAME_SELECIONAR_SERVICO)->close();


            $this->servicosAdicionados = [];
        } else {
            dd($this->servicosAdicionados);
            $this->ordemServicoForm->servicos = $this->servicosAdicionados;


            $this->dispatch(AdicionarServicosForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, servicos: $this->ordemServicoForm->servicos);

            \Flux::modal(AdicionarServicosForm::MODAL_NAME_SELECIONAR_SERVICO)->close();
        }

    }

    public function mount()
    {
        $this->id = request()->route('id') ?? null;


        if ($this->id) {

            $this->persistence = Persistence::UPDATE;


        }
    }

    public function removeServico(int $index): void
    {


        if (isset($this->servicosAdicionados[$index])) {

            unset($this->servicosAdicionados[$index]);
            $this->servicosAdicionados = array_values($this->servicosAdicionados);
        }
    }

    #[On(AdicionarServicosForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_SERVICO)]
    public function openModalSelecionarServico(string $modalName, mixed $idServico = null): void
    {

        if ($idServico) {
            $this->form->idServicoSelecionado = $idServico;

            $servico = \App\Models\OrdemServicoModel::query()
                ->whereHas('servicos', function ($query) use ($idServico) {
                    $query->where('tb_ordem_servico_servico.id', $idServico);
                })
                ->first();

            if ($servico) {
                $pivotData = $servico->servicos()
                    ->wherePivot('id', $idServico)
                    ->first();

                $this->form->id_servico   = $pivotData->pivot->idServico;
                $this->form->quantidade    = $pivotData->pivot->quantidade;
                $this->form->valorUnitario = $pivotData->pivot->valorUnitario;


            }
            $this->persistence = Persistence::UPDATE;
        } else {

            $this->form->servicos = \App\Models\ServicosModel::query()->where('removido', false)->get();


            $this->persistence = Persistence::CREATE;
        }

        Flux::modal($modalName)->show();
    }

    #[On(AdicionarServicosForm::EVENT_PERSISTED)]
    public function with()
    {
        $servicos = \App\Models\ServicosModel::query()
            ->get();

        return [
            'servicos' => $servicos,
        ];
    }


};
?>

<div x-data>
    <form wire:submit.prevent="save">
        <flux:text>Selecione o Serviço</flux:text>

        <hr class="w-full h-px bg-accent">

        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 my-4">
            <flux:select label="Servicos*" variant="listbox" searchable
                         wire:model="form.id_servico"
                         placeholder="Selecione"
                         name="id_servico"
                         wire:change="atualizarValorUnitario"
                         :disabled="$persistence === Persistence::UPDATE"
            >

                <flux:select.option value="">Selecione</flux:select.option>

                @foreach($servicos as $servico)

                    <flux:select.option
                        value="{{$servico->id}}">{{$servico->nome}}</flux:select.option>
                @endforeach


            </flux:select>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-4">

            <flux:input label="Quantidade*" placeholder="Digite a quantidade"
                        wire:model="form.quantidade"
                        name="quantidade"
            />
            <flux:input label="Valor Unitario*" placeholder="Digite o valor"
                        wire:model="form.valorUnitario"
                        name="valorUnitario"
            />
        </div>

        @if($persistence === Persistence::CREATE)

            <flux:button wire:click="addServicos" variant="primary" class="mt-2">Adicionar</flux:button>

        @else
            <flux:button wire:click="editarServicos" variant="primary" class="mt-2">Atualizar</flux:button>
        @endif
    </form>

    @if(count($servicosAdicionados) >0 )

        <flux:table class="">
            <flux:table.columns>

                <flux:table.column>Codigo</flux:table.column>
                <flux:table.column>Nome</flux:table.column>
                <flux:table.column>Quantidade</flux:table.column>
                <flux:table.column>Valor Unitario</flux:table.column>
                <flux:table.column>Valor Total</flux:table.column>


            </flux:table.columns>

            <flux:table.rows>
                @foreach($servicosAdicionados as $servico)

                    <flux:table.row>
                        <flux:table.cell>{{$servico['codigo']}}</flux:table.cell>
                        <flux:table.cell>{{$servico['nome']}}</flux:table.cell>
                        <flux:table.cell>{{$servico['quantidade']}}</flux:table.cell>
                        <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($servico['valorUnitario'])}}</flux:table.cell>
                        <flux:table.cell>{{Helper::formatarValorMonetarioPtBr($servico['valorTotal'])}}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button wire:click="removeServico({{$loop->index}})" variant="danger" class="mt-2">
                                <flux:icon icon="trash"/>
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>


        </flux:table>

        <div class="flex justify-end mt-4">
            <flux:button wire:click="save()" variant="primary" class="mt-2">Salvar</flux:button>
        </div>
    @endif
</div>

