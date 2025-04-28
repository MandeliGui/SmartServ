<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\TecnicoForm;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public TecnicoForm  $form;
    public ?Persistence $persistence = Persistence::CREATE;

    public function save(): void
    {

        if ($this->persistence == Persistence::UPDATE) {

            $cliente = $this->form->update();

            $this->dispatch(TecnicoForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, cliente: $cliente);
        } else {

            $cliente = $this->form->create();


            $this->dispatch(TecnicoForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, cliente: $cliente);
        }

    }

    public function mount()
    {
        $this->id = request()->route('id') ?? null;

        if ($this->id) {

            $this->persistence = Persistence::UPDATE;
            $this->form->setTecnico($this->id);

        }
    }


};
?>

<div x-data>
    <form wire:submit.prevent="save">
        <h3 class="text-smartserv-font-main font-semibold text-smartserv-color-primary-1000 dark:text-smartserv-color-primary-dark-1000 text-base">
            Informações pessoais
        </h3>

        <hr class="w-full h-px bg-smartserv-color-primary-1000 border-0 dark:bg-smartserv-color-primary-dark-1000">


        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 my-4">
            <div class="flex flex-col whitespace-nowrap col-span-6 md:col-span-6">
                <x-input-label>
                    {{ __('Nome*') }}
                </x-input-label>
                <x-text-input placeholder="Digite o nome" x-mask="" wire:model="form.nome"/>
                <x-input-error :messages="$errors->get('nome')" class="mt-2 text-wrap"/>
            </div>
            <div class="flex flex-col whitespace-nowrap col-span-6 md:col-span-6">
                <x-input-label>
                    {{ __('Telefone*') }}
                </x-input-label>
                <x-text-input placeholder="(00) 00000-0000" x-mask="(99) 99999-9999" wire:model="form.telefone"
                />
                <x-input-error :messages="$errors->get('telefone')" class="mt-2 text-wrap"/>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 my-4">
            <div class="flex flex-col whitespace-nowrap col-span-4 md:col-span-4">
                <x-input-label>
                    {{ __('Cpf') }}
                </x-input-label>

                <x-text-input placeholder="insira aqui o cpf"
                              x-mask="999.999.999-99"
                              wire:model="form.cpf"/>


                <x-input-error :messages="$errors->get('cpf')" class="mt-2 text-wrap"/>

            </div>
            <div class="flex flex-col whitespace-nowrap col-span-4 md:col-span-4">
                <x-input-label>
                    {{ __('Data de Nascimento') }}
                </x-input-label>
                <x-text-input placeholder="00/00/0000" x-mask="99/99/9999" wire:model="form.dataNascimento"
                />
                <x-input-error :messages="$errors->get('dataNascimento')" class="mt-2 text-wrap"/>
            </div>
            <div class="flex flex-col whitespace-nowrap col-span-4 md:col-span-4">
                <x-input-label>
                    {{ __('E-mail') }}
                </x-input-label>
                <x-text-input placeholder="exemplo@dominio.com" wire:model="form.email"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-wrap"/>
            </div>
        </div>

        <h3 class="text-smartserv-font-main font-semibold text-smartserv-color-primary-1000 dark:text-smartserv-color-primary-dark-1000 text-base">
            Endereço
        </h3>

        <hr class="w-full h-px bg-smartserv-color-primary-1000 border-0 dark:bg-smartserv-color-primary-dark-1000">

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 my-4">
            <div class="flex flex-col whitespace-nowrap col-span-3 md:col-span-3">
                <x-input-label>
                    {{ __('CEP') }}
                </x-input-label>
                <x-text-input placeholder="00000-000" x-mask="99999-999" wire:model="form.endereco.cep"
                />
                <x-input-error :messages="$errors->get('endereco.cep')" class="mt-2 text-wrap"/>
            </div>
            <div class="flex flex-col whitespace-nowrap col-span-6 md:col-span-6">
                <x-input-label>
                    {{ __('Rua') }}
                </x-input-label>
                <x-text-input placeholder="Digite a rua" wire:model="form.endereco.rua"/>
                <x-input-error :messages="$errors->get('endereco.rua')" class="mt-2 text-wrap"/>
            </div>
            <div class="flex flex-col whitespace-nowrap col-span-3 md:col-span-3">
                <x-input-label>
                    {{ __('Número') }}
                </x-input-label>
                <x-text-input placeholder="Digite o número" wire:model="form.endereco.numero"
                />
                <x-input-error :messages="$errors->get('endereco.numero')" class="mt-2 text-wrap"/>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 my-4">
            <div class="flex flex-col whitespace-nowrap col-span-3 md:col-span-3">
                <x-input-label>
                    {{ __('Complemento') }}
                </x-input-label>
                <x-text-input placeholder="Digite o complemento" wire:model="form.endereco.complemento"
                />
                <x-input-error :messages="$errors->get('endereco.complemento')" class="mt-2 text-wrap"/>
            </div>
            <div class="flex flex-col whitespace-nowrap col-span-3 md:col-span-3">
                <x-input-label>
                    {{ __('Bairro') }}
                </x-input-label>
                <x-text-input placeholder="Digite o bairro" wire:model="form.endereco.bairro"
                />
                <x-input-error :messages="$errors->get('endereco.bairro')" class="mt-2 text-wrap"/>
            </div>
            <div class="flex flex-col whitespace-nowrap col-span-3 md:col-span-3">
                <x-input-label>
                    {{ __('Cidade') }}
                </x-input-label>
                <x-text-input placeholder="Digite a cidade" wire:model="form.endereco.cidade"
                />
                <x-input-error :messages="$errors->get('endereco.cidade')" class="mt-2 text-wrap"/>
            </div>
            <div class="flex flex-col whitespace-nowrap col-span-3 md:col-span-3">
                <x-input-label>
                    {{ __('UF') }}
                </x-input-label>
                <x-select wire:model="form.endereco.uf">
                    <option value="">Selecione</option>
                    <option value="PR">PR</option>
                </x-select>
                <x-input-error :messages="$errors->get('endereco.uf')" class="mt-2 text-wrap"/>
            </div>
        </div>

        <x-save-button/>
    </form>
</div>

