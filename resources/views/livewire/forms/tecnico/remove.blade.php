<?php

use App\Enums\Persistence;

use App\Livewire\Forms\TecnicoForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public TecnicoForm $form;

    public Persistence $persistence;


    public function remove(): void
    {
        $this->form->remove();

        $this->dispatch(TecnicoForm::EVENT_PERSISTED, persistence: Persistence::REMOVE->value);

        $this->dispatch('close-modal', TecnicoForm::MODAL_NAME_REMOVE);
    }

    #[On(TecnicoForm::EVENT_NAME_SHOW_MODAL_REMOVE)]
    public function openModalRemove(string $modalName, int $id): void
    {


        $this->form->reset();

        $this->form->setTecnico($id);

        $this->persistence = Persistence::REMOVE;

        $this->dispatch('open-modal', $modalName);
    }


};

?>

<div>
    <form wire:submit.prevent="remove">

            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-smartserv-font-main text-smartserv-color-dark-1000 dark:text-smartserv-color-cinza-light-1000">
                    Tem certeza que deseja remover o tecnico
                    <strong class="text-smartserv-color-primary-1000 ">
                        {{ $this->form->nome ?? '' }}
                    </strong>?
                </h3>
                <div class="flex items-center justify-center gap-3">
                    <x-secondary-button x-on:click="$dispatch('close-modal', '{{ TecnicoForm::MODAL_NAME_REMOVE }}')">
                        <svg class="w-5 h-5 me-1 text-white dark:text-white" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18 17.94 6M18 18 6.06 6"/>
                        </svg>
                        <span>Cancelar</span>
                    </x-secondary-button>
                    <x-delete-button wire:loading.attr="disabled"/>
                </div>
            </div>
    </form>
</div>
