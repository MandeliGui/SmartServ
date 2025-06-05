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

        try {

            if ($this->persistence == Persistence::UPDATE) {

                $cliente = $this->form->update();


                Flux::toast('Cliente atualizado com sucesso!', variant: 'success');
                $this->redirect(route('clientes'), navigate: true);

            } else {

                $cliente = $this->form->create();


                Flux::toast('Cliente cadastrado com sucesso!', variant: 'success');

                $this->redirect(route('clientes'), navigate: true);

            }
        } catch (Throwable $e) {
            Flux::toast('Erro ao cadastrar cliente!', variant: 'danger');
            throw $e;
        }

    }

    public function buscarCnpj()
    {

        $dadosEmpresa = Helper::obterDadosEmpresaPorCnpj($this->form->cpfCnpj);

        if ($dadosEmpresa) {
            $this->form->nomeRazaoSocial    = $dadosEmpresa->company->name;
            $this->form->nomeFantasia       = $dadosEmpresa->alias;
            $this->form->endereco['cep']    = $dadosEmpresa->address->zip;
            $this->form->endereco['rua']    = $dadosEmpresa->address->street;
            $this->form->endereco['numero'] = $dadosEmpresa->address->number;
            $this->form->endereco['bairro'] = $dadosEmpresa->address->district;
            $this->form->endereco['cidade'] = $dadosEmpresa->address->city;
            $this->form->endereco['uf']     = $dadosEmpresa->address->state;
        } else {
            $this->form->nomeRazaoSocial    = null;
            $this->form->nomeFantasia       = null;
            $this->form->endereco['rua']    = null;
            $this->form->endereco['numero'] = null;
            $this->form->endereco['bairro'] = null;
            $this->form->endereco['cidade'] = null;
            $this->form->endereco['uf']     = null;
        }
    }

    public function buscarCep()
    {

        $endereco = Helper::obterEnderecoPorCep($this->form->endereco['cep']);


        if ($endereco) {
            $this->form->endereco['rua']         = $endereco->logradouro ?? null;
            $this->form->endereco['complemento'] = $endereco->complemento ?? null;
            $this->form->endereco['bairro']      = $endereco->bairro ?? null;
            $this->form->endereco['cidade']      = $endereco->localidade ?? null;
            $this->form->endereco['uf']          = $endereco->uf ?? null;
        } else {
            $this->form->endereco['rua']         = null;
            $this->form->endereco['complemento'] = null;
            $this->form->endereco['bairro']      = null;
            $this->form->endereco['cidade']      = null;
            $this->form->endereco['uf']          = null;
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

    public function with()
    {
        return [
            'grupoClientes' => \App\Models\GrupoClienteModel::query()->whereRemovido(false)->get()
        ];
    }


};
?>

<div x-data>
    <form wire:submit.prevent="save">
        <flux:text>Informações pessoais</flux:text>

        <hr class="w-full h-px bg-accent">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
            {{--      ESSE IF SERVE PARA A MASCARA FUNCIONAR NA TELA DE EDICAO, SE NAO ELE NAO RECONHECE O INPUT LENGTH      --}}
            @if($persistence->value === Persistence::CREATE->value)

                <flux:input x-mask:dynamic="$input.length <= 14 ? '999.999.999-99' : '99.999.999/9999-99'"
                            description="*Preencha com um cnpj para buscar dados automaticamente"
                            label="Cpf/Cnpj*"
                            placeholder="Cpf/Cnpj"
                            wire:model="form.cpfCnpj"
                            name="cpfCnpj"
                            wire:blur="buscarCnpj"
                            wire:keydown.enter.prevent="buscarCnpj"
                />

            @else

                @if(strlen($this->form->cpfCnpj) < 14)
                    <flux:input x-mask:dynamic="'999.999.999-99'"
                                label="Cpf*"
                                placeholder="Cpf"
                                wire:model="form.cpfCnpj"
                                name="cpfCnpj"
                                disabled
                    />
                @else
                    <flux:input x-mask:dynamic="'99.999.999/9999-99'"
                                label="Cnpj*"
                                placeholder="Cnpj"
                                wire:model="form.cpfCnpj"
                                name="cpfCnpj"
                                disabled
                    />

                @endif
            @endif

        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
            <flux:input label="Nome/Razão Social*" placeholder="Digite o nome ou razão social"
                        wire:model="form.nomeRazaoSocial"
                        name="nomeRazaoSocial"
                        wire:loading.attr="disabled"
                        wire:target="buscarCnpj"/>

            <flux:input label="Nome Fantasia" placeholder="Digite o nome fantasia"
                        wire:model="form.nomeFantasia"
                        name="nomeFantasia"
                        wire:loading.attr="disabled"
                        wire:target="buscarCnpj"/>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-4">
            <flux:input label="Telefone*" placeholder="(00) 00000-0000"
                        mask="(99) 99999-9999"
                        wire:model="form.telefone"
                        name="telefone"
                        wire:loading.attr="disabled"
                        wire:target="buscarCnpj"/>

            <flux:date-picker wire:model="date">
                <x-slot name="trigger">
                    <flux:date-picker.input label="Data de Nascimento" placeholder="00/00/0000"
                                            wire:model="form.dataNascimento"
                                            name="dataNascimento"
                                            wire:loading.attr="disabled"
                                            wire:target="buscarCnpj"/>
                </x-slot>
            </flux:date-picker>

            <flux:input label="E-mail" placeholder="exemplo@dominio.com"
                        wire:model="form.email"
                        name="email"
                        wire:loading.attr="disabled"
                        wire:target="buscarCnpj"/>

            <flux:select variant="listbox" label="Grupo de Clientes"
                         wire:model="form.idGrupo"
                         placeholder="Selecione"
                         searchable
                         name="idGrupo"
                         wire:loading.attr="disabled"
                         wire:target="buscarCnpj">
                <flux:select.option value="">Selecione</flux:select.option>
                @foreach($grupoClientes as $grupoCliente)
                    <flux:select.option value="{{ $grupoCliente->id }}">{{ $grupoCliente->nome }}</flux:select.option>

                @endforeach
            </flux:select>
        </div>

        <flux:text>Endereço</flux:text>

        <hr class="w-full h-px bg-accent">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
            <flux:input label="CEP*" placeholder="00000-000"
                        mask="99999-999"
                        wire:model="form.endereco.cep"
                        name="endereco.cep"
                        wire:blur="buscarCep"
                        wire:keydown.enter.prevent="buscarCep"
                        wire:loading.attr="disabled"
                        wire:target="buscarCnpj,buscarCep"/>

            <flux:input label="Rua*" placeholder="Digite a rua"
                        wire:model="form.endereco.rua"
                        name="endereco.rua"
                        wire:loading.attr="disabled"
                        wire:target="buscarCnpj,buscarCep"/>

            <flux:input label="Número*" placeholder="Digite o número"
                        wire:model="form.endereco.numero"
                        name="endereco.numero"
                        wire:loading.attr="disabled"
                        wire:target="buscarCnpj,buscarCep"/>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-4">
            <flux:input label="Complemento" placeholder="Digite o complemento"
                        wire:model="form.endereco.complemento"
                        name="endereco.complemento"
                        wire:loading.attr="disabled"
                        wire:target="buscarCnpj,buscarCep"/>

            <flux:input label="Bairro*" placeholder="Digite o bairro"
                        wire:model="form.endereco.bairro"
                        name="endereco.bairro"
                        wire:loading.attr="disabled"
                        wire:target="buscarCnpj,buscarCep"/>

            <flux:input label="Cidade*" placeholder="Digite a cidade"
                        wire:model="form.endereco.cidade"
                        name="endereco.cidade"
                        wire:loading.attr="disabled"
                        wire:target="buscarCnpj,buscarCep"/>

            <flux:select label="UF*" variant="listbox" searchable
                         wire:model="form.endereco.uf"
                         placeholder="Selecione"
                         name="endereco.uf"
                         wire:loading.attr="disabled"
                         wire:target="buscarCnpj,buscarCep">
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

        <flux:button type="submit" variant="primary" class="mt-2">Salvar</flux:button>
    </form>
</div>

