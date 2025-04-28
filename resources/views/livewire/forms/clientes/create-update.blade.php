<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\ClientesForm;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public ClientesForm $form;
    public ?Persistence $persistence = Persistence::CREATE;

    public function save(): void
    {
        if ($this->persistence == Persistence::UPDATE) {

            $cliente = $this->form->update();

            $this->dispatch(ClientesForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, cliente: $cliente);
        } else {

            $cliente = $this->form->create();


            $this->dispatch(ClientesForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, cliente: $cliente);
        }

    }

    public function buscarCnpj()
    {

        $dadosEmpresa = Helper::obterDadosEmpresaPorCnpj($this->form->cpfCnpj);

        if ($dadosEmpresa) {
            $this->form->nomeRazaoSocial = $dadosEmpresa->company->name;
            $this->form->nomeFantasia = $dadosEmpresa->alias;
            $this->form->endereco['cep'] = $dadosEmpresa->address->zip;
            $this->form->endereco['rua'] = $dadosEmpresa->address->street;
            $this->form->endereco['numero'] = $dadosEmpresa->address->number;
            $this->form->endereco['complemento'] = $dadosEmpresa->address->details;
            $this->form->endereco['bairro'] = $dadosEmpresa->address->district;
            $this->form->endereco['cidade'] = $dadosEmpresa->address->city;
            $this->form->endereco['uf'] = $dadosEmpresa->address->state;
        }
    }

//    public function with()
//    {
//
//    }

    public function mount()
    {
        $this->id = request()->route('id') ?? null;

        if ($this->id) {

            $this->persistence = Persistence::UPDATE;
            $this->form->setCliente($this->id);

        }
    }


};
?>

<div x-data>
    <form wire:submit.prevent="save">
        <flux:text>Informações pessoais</flux:text>


        <hr class="w-full h-px bg-accent">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
            <flux:input x-mask:dynamic="$input.length <= 14 ? '999.999.999-99' : '99.999.999/9999-99'"
                        label="Cpf/Cnpj"
                        placeholder="Cpf/Cnpj"
                        wire:model="form.cpfCnpj"
            wire:blur="buscarCnpj"/>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
            <flux:input label="Nome/Razão Social" placeholder="Digite o nome ou razão social"
                        wire:model="form.nomeRazaoSocial"/>
            <flux:input label="Nome Fantasia" placeholder="Digite o nome fantasia" wire:model="form.nomeFantasia"/>

        </div>
        <div class=" grid grid-cols-1 md:grid-cols-4 gap-4 my-4">

            <flux:input label="Telefone*" placeholder="(00) 00000-0000"
                        mask="(99) 99999-9999"
                        wire:model="form.telefone"/>
            <flux:input label="Data de Nascimento" placeholder="00/00/0000"
                        mask="99/99/9999"
                        wire:model="form.dataNascimento"/>
            <flux:input label="E-mail*" placeholder="exemplo@dominio.com"
                        wire:model="form.email"/>


            <flux:select variant="listbox" label="Grupo de Clientes*" wire:model="form.grupoClientes"
                         placeholder="Selecione" searchable>
            </flux:select>


        </div>


        <flux:text>Endereço</flux:text>


        <hr class="w-full h-px bg-accent">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
            <flux:input label="CEP*" placeholder="00000-000"
                        mask="99999-999"
                        wire:model="form.endereco.cep"/>
            <flux:input label="Rua*" placeholder="Digite a rua" wire:model="form.endereco.rua"/>
            <flux:input label="Número*" placeholder="Digite o número" wire:model="form.endereco.numero"/>



        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-4">
            <flux:input label="Complemento" placeholder="Digite o complemento"
                        wire:model="form.endereco.complemento"/>
            <flux:input label="Bairro*" placeholder="Digite o bairro" wire:model="form.endereco.bairro"/>
            <flux:input label="Cidade*" placeholder="Digite a cidade" wire:model="form.endereco.cidade"/>
            <flux:select label="UF*" variant="listbox" searchable wire:model="form.endereco.uf" placeholder="Selecione">
                <flux:select.option value="AC">AC</flux:select.option>
                <flux:select.option value="AL">AL</flux:select.option>
                <flux:select.option value="AP">AP</flux:select.option>
                <flux:select.option value="AM">AM</flux:select.option>
                <flux:select.option value="BA">BA</flux:select.option>
                <flux:select.option value="CE">CE</flux:select.option>
                <flux:select.option value="DF">DF</flux:select.option>
                <flux:select.option value="ES">ES</flux:select.option>
                <flux:select.option value="GO">GO</flux:select.option>
                <flux:select.option value="MA">MA</flux:select.option>
                <flux:select.option value="MT">MT</flux:select.option>
                <flux:select.option value="MS">MS</flux:select.option>
                <flux:select.option value="MG">MG</flux:select.option>
                <flux:select.option value="PA">PA</flux:select.option>
                <flux:select.option value="PB">PB</flux:select.option>
                <flux:select.option value="PR">PR</flux:select.option>
                <flux:select.option value="PE">PE</flux:select.option>
                <flux:select.option value="PI">PI</flux:select.option>
                <flux:select.option value="RJ">RJ</flux:select.option>
                <flux:select.option value="RN">RN</flux:select.option>
                <flux:select.option value="RS">RS</flux:select.option>
                <flux:select.option value="RO">RO</flux:select.option>
                <flux:select.option value="RR">RR</flux:select.option>
                <flux:select.option value="SC">SC</flux:select.option>
                <flux:select.option value="SP">SP</flux:select.option>
                <flux:select.option value="SE">SE</flux:select.option>
                <flux:select.option value="TO">TO</flux:select.option>

            </flux:select>

        </div>

        <flux:button type="submit" variant="primary">Primary</flux:button>
    </form>
</div>

