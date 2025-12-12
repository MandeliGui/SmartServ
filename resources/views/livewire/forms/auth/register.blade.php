<?php

use App\Enums\FormaPagamentoContratacaoEnum;
use App\Enums\Persistence;
use App\Enums\TipoPessoaEnum;
use App\Http\Requests\Tenant\AuthRequest;
use App\Livewire\Forms\RegisterForm;
use Carbon\Carbon;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;


    public RegisterForm $form;
    public ?Persistence $persistence = Persistence::CREATE;

    public function register(): void
    {

        $this->form->create();

        $this->redirect('login', navigate: true);


    }

    public function mount(): void
    {

        $this->form->anoAtual = Carbon::now()->format('Y');
    }

    public function updatedFormTipoPessoa($value): void
    {
        if ($value == TipoPessoaEnum::PESSOA_FISICA->value) {
            $this->form->cnpj         = null;
            $this->form->razaoSocial  = null;
            $this->form->nomeFantasia = null;
        } elseif ($value == TipoPessoaEnum::PESSOA_JURIDICA->value) {
            $this->form->cpf = null;
        }
    }

    public function updatedFormFormaPagamento($value): void
    {
        if ($value == FormaPagamentoContratacaoEnum::BOLETO->value) {
            $this->form->nomeImpresso = null;
            $this->form->numeroCartao = null;
            $this->form->mesExpiracao = null;
            $this->form->anoExpiracao = null;
            $this->form->ccv          = null;
        }

    }
}
?>

<div>
    <form wire:submit="register">

        <flux:fieldset>


            <div class="mt-4 mb-6 flex items-center justify-center">

                <flux:heading class="flex items-center  text-accent" size="xl">Contratação de sistema</flux:heading>

            </div>
            <flux:separator></flux:separator>

            <div class="space-y-6 mt-6">
                <flux:heading class="flex items-center gap-2 text-accent" size="lg">Informações pessoais</flux:heading>
                <div class="grid grid-cols-3 gap-x-4 gap-y-6">
                    <flux:radio.group wire:model.live="form.tipoPessoa" label="Tipo Pessoa" variant="segmented">
                        <flux:radio label="Pessoa Jurídica" value="{{TipoPessoaEnum::PESSOA_JURIDICA->value}}"/>
                        <flux:radio label="Pessoa Fisica" value="{{TipoPessoaEnum::PESSOA_FISICA->value}}"/>
                    </flux:radio.group>
                </div>

                <div class="grid grid-cols-2 gap-x-4 gap-y-6">
                    <flux:input label="Nome Completo*" placeholder="Insira seu nome aqui..." wire:model="form.nomeCliente" name="nomeCliente"/>
                    <flux:input label="Telefone Whatsapp*" placeholder="Insira seu telefone aqui..." wire:model="form.telefone" name="telefone" x-mask="(99) 9 9999-9999"/>
                    <flux:input label="Email*" placeholder="Insira seu email aqui..." wire:model="form.email" name="email"/>

                    @if($this->form->tipoPessoa == TipoPessoaEnum::PESSOA_JURIDICA->value)
                        <flux:input label="CNPJ*" placeholder="Insira seu CNPJ aqui..." wire:model="form.cnpj" name="cnpj" x-mask="99.999.999/9999-99"/>
                        <flux:input label="Razão Social*" placeholder="Insira a razão social aqui..." wire:model="form.razaoSocial"/>
                        <flux:input label="Nome Fantasia*" placeholder="Insira o nome fantasia aqui..." wire:model="form.nomeFantasia"/>
                        <div class="col-span-2">

                            <flux:select label="Tipo Empresa" wire:model="form.tipoEmpresa" name="tipoEmpresa">
                                <flux:select.option>Selecione</flux:select.option>
                                <flux:select.option value="MEI">MEI</flux:select.option>
                                <flux:select.option value="LTDA">LTDA</flux:select.option>
                                <flux:select.option value="INDIVIDUAL">INDIVIDUAL</flux:select.option>
                                <flux:select.option value="ASSOCIAÇÃO">ASSOCIAÇÃO</flux:select.option>
                                <flux:select.option value="OUTRO">OUTRO</flux:select.option>
                            </flux:select>
                        </div>
                    @elseif($this->form->tipoPessoa == TipoPessoaEnum::PESSOA_FISICA->value)
                        <flux:input label="CPF*" placeholder="Insira seu CPF aqui..." wire:model="form.cpf" name="cpf" x-mask="999.999.999-99"/>

                    @endif
                </div>

                <flux:separator></flux:separator>

                <flux:heading class="flex items-center gap-2 text-accent" size="lg">Endereço</flux:heading>
                <div class="grid grid-cols-3 gap-x-4 gap-y-6">
                    <flux:input label="CEP*" placeholder="Insira seu cep aqui..." wire:model="form.endereco.cep" name="endereco.cep" x-mask="99999-999"/>
                    <flux:input label="Rua*" placeholder="Insira sua rua aqui..." wire:model="form.endereco.rua" name="endereco.rua"/>
                    <flux:input label="Numero*" placeholder="Insira seu numero aqui..." wire:model="form.endereco.numero" name="endereco.numero"/>
                </div>
                <div class="grid grid-cols-3 gap-x-4 gap-y-6">
                    <flux:input label="Bairro*" placeholder="Insira seu bairro aqui..." wire:model="form.endereco.bairro" name="endereco.bairro"/>
                    <flux:input label="Complemento" placeholder="Insira seu complemento aqui..." wire:model="form.endereco.complemento" name="endereco.complemento"/>
                    <flux:input label="Cidade*" placeholder="Insira sua cidade aqui..." wire:model="form.endereco.cidade" name="endereco.cidade"/>


                    <flux:select label="UF*" variant="listbox" searchable placeholder="Selecione seu estado..." wire:model="form.endereco.uf" name="endereco.uf">
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
                <flux:separator></flux:separator>
                <flux:heading class="flex items-center gap-2 text-accent" size="lg">Informações de pagamento</flux:heading>
                <div class="grid grid-cols-3 gap-x-4 gap-y-6">
                    <flux:radio.group wire:model.live="form.formaPagamento" label="Forma Pagamento" variant="segmented">
                        <flux:radio label="Cartão de crédito" value="{{FormaPagamentoContratacaoEnum::CREDIT_CARD->value}}"/>
                        <flux:radio label="Boleto" value="{{FormaPagamentoContratacaoEnum::BOLETO->value}}"/>
                    </flux:radio.group>
                </div>

                @if($this->form->formaPagamento == FormaPagamentoContratacaoEnum::CREDIT_CARD->value)
                    <div class="grid grid-cols-2 gap-x-4 gap-y-6">
                        <flux:input label="Nome Impresso*" placeholder="Nome impresso no cartão..." wire:model="form.dadosCartao.nomeImpresso" name="dadosCartao.nomeImpresso"/>
                        <flux:input label="Número do cartão*" placeholder="Número do cartão..." wire:model="form.dadosCartao.numeroCartao" name="dadosCartao.numeroCartao" x-mask="9999 9999 9999 9999"/>
                    </div>
                    <div class="grid grid-cols-3 gap-x-4 gap-y-6">
                        <flux:select label="Mês de expiração*" placeholder="Insira seu numero aqui..." variant="listbox" searchable wire:model="form.dadosCartao.mesExpiracao" name="dadosCartao.mesExpiracao">
                            <flux:select.option value="01">01</flux:select.option>
                            <flux:select.option value="02">02</flux:select.option>
                            <flux:select.option value="03">03</flux:select.option>
                            <flux:select.option value="04">04</flux:select.option>
                            <flux:select.option value="05">05</flux:select.option>
                            <flux:select.option value="06">06</flux:select.option>
                            <flux:select.option value="07">07</flux:select.option>
                            <flux:select.option value="08">08</flux:select.option>
                            <flux:select.option value="09">09</flux:select.option>
                            <flux:select.option value="10">10</flux:select.option>
                            <flux:select.option value="11">11</flux:select.option>
                            <flux:select.option value="12">12</flux:select.option>

                        </flux:select>
                        <flux:select label="Ano de expiração*" placeholder="Insira seu numero aqui..." variant="listbox" searchable wire:model="form.dadosCartao.anoExpiracao" name="dadosCartao.anoExpiracao">

                            @for($i = $this->form->anoAtual; $i < $this->form->anoAtual + 60; $i++)

                                <flux:select.option value="{{$i}}">{{$i}}</flux:select.option>
                            @endfor

                        </flux:select>

                        <flux:input label="CCV*" placeholder="Código de segurança do cartão..." wire:model="form.dadosCartao.ccv" name="dadosCartao.ccv" x-mask="999"/>

                    </div>

                @elseif($this->form->formaPagamento == FormaPagamentoContratacaoEnum::BOLETO->value)
                    <flux:card size="sm" class="hover:bg-zinc-50 dark:hover:bg-zinc-700 flex items-center justify-center">
                        <flux:heading class="flex items-center gap-2" size="xl">
                            Clique no botão <span class="text-accent">"CONTRATAR"</span> abaixo para gerar seu boleto bancário.
                        </flux:heading>

                    </flux:card>
                @endif
                <flux:separator></flux:separator>
                <flux:button wire:click="register()" variant="success">Contratar</flux:button>
            </div>
        </flux:fieldset>


    </form>
</div>
