<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\ServicosForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public ServicosForm $form;
    public ?Persistence $persistence = Persistence::CREATE;

    public function save(): void
    {
        $this->persistence = !empty($this->form->id) ? Persistence::UPDATE : Persistence::CREATE;

        if ($this->persistence == Persistence::UPDATE) {

            $servico = $this->form->update();

            $this->dispatch(ServicosForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, servico: $servico);
            $this->dispatch('close-modal', ServicosForm::MODAL_NAME_UPDATE);
        } else {

            $servico = $this->form->create();


            $this->dispatch(ServicosForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, servico: $servico);
            $this->dispatch('close-modal', ServicosForm::MODAL_NAME_CREATE);
        }

    }

    #[On(ServicosForm::EVENT_NAME_SHOW_MODAL_CREATE)]
    public function openModalCreate(string $modalName)
    {
        $this->form->reset();


        $this->dispatch('open-modal', $modalName);
    }

    #[On(ServicosForm::EVENT_NAME_SHOW_MODAL_UPDATE)]
    public function openModalUpdate(string $modalName, int $id)
    {
        $this->form->reset();


        $this->form->setServico($id);


        $this->dispatch('open-modal', $modalName);
    }



//    public function with()
//    {
//
//    }

    public function mount()
    {

    }


};
?>

<div x-data>
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 my-4">
            <div class="flex flex-col whitespace-nowrap col-span-3 md:col-span-3">
                <x-input-label>
                    {{ __('Codigo') }}
                </x-input-label>
                <x-text-input placeholder="" x-mask="" wire:model="form.codigo"
                />
                <x-input-error :messages="$errors->get('codigo')" class="mt-2 text-wrap"/>
            </div>
            <div class="flex flex-col whitespace-nowrap col-span-6 md:col-span-6">
                <x-input-label>
                    {{ __('Nome') }}
                </x-input-label>
                <x-text-input placeholder="Digite o nome" x-mask="" wire:model="form.nome"
                />
                <x-input-error :messages="$errors->get('nome')" class="mt-2 text-wrap"/>

            </div>
            <div class="flex flex-col whitespace-nowrap col-span-3 md:col-span-3">
                <x-input-label>
                    {{ __('Valor*') }}
                </x-input-label>
                <x-text-input placeholder="" x-mask="" wire:model="form.valor"
                />
                <x-input-error :messages="$errors->get('valor')" class="mt-2 text-wrap"/>

            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 my-4">
            <div class="flex flex-col whitespace-nowrap col-span-12 md:col-span-12">
                <x-input-label>
                    {{ __('Descriçao') }}
                </x-input-label>
                <x-textarea placeholder="" x-mask="" wire:model="form.descricao"
                ></x-textarea>
                <x-input-error :messages="$errors->get('descricao')" class="mt-2 text-wrap"/>

            </div>

        </div>


        <x-save-button/>
    </form>
</div>

