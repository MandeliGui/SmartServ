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


        Validator::make($this->form->only('id_servico', 'quantidade'),
            rules: [
                'id_servico' => 'required',
                'quantidade' => 'required|numeric',
            ],
            messages: [
                'id_servico.required' => 'O campo servico é obrigatório.',
                'quantidade.required' => 'O campo quantidade é obrigatório.',
                'quantidade.numeric'  => 'O campo quantidade deve ser numérico.',
            ])->validate();

        $servico = $this->form->getServicoById($this->form->id_servico);


        $this->servicosAdicionados[] = [
            'id'            => null,
            'idServico'     => $this->form->id_servico,
            'codigo'        => $servico->codigo,
            'quantidade'    => $this->form->quantidade,
            'nome'          => $servico->nome,
            'valorUnitario' => $servico->valor,
            'valorTotal'    => $servico->valor * $this->form->quantidade,
        ];


        $this->form->id_servico = null;
        $this->form->quantidade = null;

    }

    public function save(): void
    {
        $this->ordemServicoForm->servicos = $this->servicosAdicionados;

        $this->dispatch(AdicionarServicosForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, servicos: $this->ordemServicoForm->servicos);

        \Flux::modal(AdicionarServicosForm::MODAL_NAME_SELECIONAR_SERVICO)->close();


        $this->servicosAdicionados = [];

    }

    public function mount()
    {

    }

    public function removeServico(int $index): void
    {


        if (isset($this->servicosAdicionados[$index])) {

            unset($this->servicosAdicionados[$index]);
            $this->servicosAdicionados = array_values($this->servicosAdicionados);
        }
    }

    #[On(AdicionarServicosForm::EVENT_NAME_SHOW_MODAL_SELECIONAR_SERVICO)]
    public function openModalSelecionarServico(string $modalName): void
    {


        $this->form->servicos = \App\Models\ServicosModel::query()->where('removido', false)->get();


        $this->persistence = Persistence::REMOVE;

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
            >


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
        </div>


        <flux:button wire:click="addServicos" variant="primary" class="mt-2">Adicionar</flux:button>
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

