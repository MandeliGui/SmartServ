<?php

use App\Enums\Persistence;
use App\Livewire\Forms\EntradasSaidasForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public EntradasSaidasForm $form;

    public Persistence $persistence;


    public function remove(): void
    {


        $this->form->remove();

        $this->dispatch(EntradasSaidasForm::EVENT_PERSISTED, persistence: Persistence::REMOVE->value);

        Flux::modal(EntradasSaidasForm::MODAL_NAME_REMOVE)->close();
        Flux::toast('Movimento removido com sucesso!', variant: 'success');

    }

    #[On(EntradasSaidasForm::EVENT_NAME_SHOW_MODAL_REMOVE)]
    public function openModalRemove(string $modalName, int $id): void
    {

        $this->form->setEntradaSaida($id);
        Flux::modal($modalName)->show();
    }


};

?>

<div>
    <form wire:submit.prevent="remove">
        @if(isset($this->form->id))
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Remover categoria?</flux:heading>
                    <flux:text class="mt-2">
                        <p>Tem certeza que deseja remover o movimento:</p>
                        <br>
                        <span>
                       Data Vencimento: <strong>{{\App\Helpers\Helper::formatarDataPtBr($this->form->data_vencimento)}} </strong>
                   </span>
                        <br>
                        <span>
                            Tipo:
                            <strong>
                                <flux:badge
                                    color="{{ $this->form->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? 'green' : 'red' }}">
                                    {{ $this->form->tipo === \App\Enums\TipoEntradaSaida::ENTRADA->value ? 'Entrada' : 'Saída' }}
                                </flux:badge>
                            </strong>
                        </span>
                        <br>
                        <span>
                            Descrição:
                            <strong>{{ $this->form->descricao ?? (\App\Models\CategoriaEntradaSaidaModel::query()->where('id',$this->form->categoria_id)->first()->nome) }}</strong>
                        </span>
                        <br>
                        <span>
                            Valor:
                            <strong>
                                R${{ \App\Helpers\Helper::formatarValorMonetarioPtBr($this->form->valor_original) }}
                            </strong>
                        </span>


                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer/>
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancelar</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="danger">Remover</flux:button>
                </div>
            </div>
        @endif
    </form>
</div>
