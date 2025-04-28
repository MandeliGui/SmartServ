<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\GrupoClientesForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public GrupoClientesForm $form;
    public ?Persistence      $persistence = Persistence::CREATE;

    public function save(): void
    {
        $this->persistence = !empty($this->form->id) ? Persistence::UPDATE : Persistence::CREATE;

        if ($this->persistence == Persistence::UPDATE) {

            $grupo = $this->form->update();

            $this->dispatch(GrupoClientesForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, grupo: $grupo);
            $this->dispatch('close-modal', GrupoClientesForm::MODAL_NAME_UPDATE);
        } else {

            $grupo = $this->form->create();


            $this->dispatch(GrupoClientesForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, grupo: $grupo);
            $this->dispatch('close-modal', GrupoClientesForm::MODAL_NAME_CREATE);
        }

    }

    #[On(GrupoClientesForm::EVENT_NAME_SHOW_MODAL_CREATE)]
    public function openModalCreate(string $modalName)
    {
        $this->form->reset();


        $this->dispatch('open-modal', $modalName);
    }

    #[On(GrupoClientesForm::EVENT_NAME_SHOW_MODAL_UPDATE)]
    public function openModalUpdate(string $modalName, int $id)
    {
        $this->form->reset();


        $this->form->setGrupo($id);


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
            <div class="flex flex-col whitespace-nowrap col-span-12 md:col-span-12">
                <x-input-label>
                    {{ __('Nome') }}
                </x-input-label>
                <x-text-input placeholder="Digite o nome" x-mask="" wire:model="form.nome"
                />
                <x-input-error :messages="$errors->get('nome')" class="mt-2 text-wrap"/>

            </div>
        </div>


        <x-save-button/>
    </form>
</div>

