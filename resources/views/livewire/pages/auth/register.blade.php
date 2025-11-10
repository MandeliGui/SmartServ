<?php

use App\Enums\FormaPagamentoContratacaoEnum;
use App\Enums\TipoPessoaEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.register')]
class extends Component {
    public string $tipoPessoa = 'PJ';
    public        $nomeCliente;
    public        $cpfCnpj;

    public string $formaPagamento = "CREDIT_CARD";

    public $anoAtual;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::loin($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function mount(): void
    {
        $this->anoAtual = Carbon::now()->format('Y');
    }
}; ?>

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
                    <flux:radio.group wire:model.live="tipoPessoa" label="Tipo Pessoa" variant="segmented">
                        <flux:radio label="Pessoa Jurídica" value="{{TipoPessoaEnum::PESSOA_JURIDICA->value}}"/>
                        <flux:radio label="Pessoa Fisica" value="{{TipoPessoaEnum::PESSOA_FISICA->value}}"/>
                    </flux:radio.group>
                </div>

                <div class="grid grid-cols-2 gap-x-4 gap-y-6">
                    <flux:input label="Nome Completo" placeholder="Insira seu nome aqui..."/>
                    <flux:input label="Telefone" placeholder="Insira seu telefone aqui..."/>
                    <flux:input label="Email" placeholder="Insira seu email aqui..."/>

                    @if($tipoPessoa == TipoPessoaEnum::PESSOA_JURIDICA->value)
                        <flux:input label="CNPJ" placeholder="Insira seu CNPJ aqui..."/>
                        <flux:input label="Razão Social" placeholder="Insira a razão social aqui..."/>
                        <flux:input label="Nome Fantasia" placeholder="Insira o nome fantasia aqui..."/>
                        <div class="col-span-2">

                            <flux:select label="Tipo Empresa">
                                <flux:select.option>MEI</flux:select.option>
                                <flux:select.option>LTDA</flux:select.option>
                                <flux:select.option>INDIVIDUAL</flux:select.option>
                                <flux:select.option>ASSOCIAÇÃO</flux:select.option>
                                <flux:select.option>OUTRO</flux:select.option>
                            </flux:select>
                        </div>
                    @elseif($tipoPessoa == TipoPessoaEnum::PESSOA_FISICA->value)
                        <flux:input label="CPF" placeholder="Insira seu CPF aqui..."/>

                    @endif
                </div>

                <flux:separator></flux:separator>

                <flux:heading class="flex items-center gap-2 text-accent" size="lg">Endereço</flux:heading>
                <div class="grid grid-cols-3 gap-x-4 gap-y-6">
                    <flux:input label="CEP" placeholder="Insira seu cep aqui..."/>
                    <flux:input label="Rua" placeholder="Insira sua rua aqui..."/>
                    <flux:input label="Numero" placeholder="Insira seu numero aqui..."/>
                </div>
                <div class="grid grid-cols-3 gap-x-4 gap-y-6">
                    <flux:input label="Bairro" placeholder="Insira seu bairro aqui..."/>
                    <flux:input label="Complemento" placeholder="Insira seu complemento aqui..."/>
                    <flux:input label="Cidade" placeholder="Insira sua cidade aqui..."/>


                    <flux:select label="UF" variant="listbox" searchable placeholder="Selecione seu estado...">
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
                    <flux:radio.group wire:model.live="formaPagamento" label="Forma Pagamento" variant="segmented">
                        <flux:radio label="Cartão de crédito" value="{{FormaPagamentoContratacaoEnum::CREDIT_CARD->value}}"/>
                        <flux:radio label="Boleto" value="{{FormaPagamentoContratacaoEnum::BOLETO->value}}"/>
                    </flux:radio.group>
                </div>

                @if($formaPagamento == FormaPagamentoContratacaoEnum::CREDIT_CARD->value)
                    <div class="grid grid-cols-2 gap-x-4 gap-y-6">
                        <flux:input label="Nome Impresso" placeholder="Nome impresso no cartão..."/>
                        <flux:input label="Número do cartão" placeholder="Número do cartão..."/>
                    </div>
                    <div class="grid grid-cols-3 gap-x-4 gap-y-6">
                        <flux:select label="Mês de expiração" placeholder="Insira seu numero aqui..." variant="listbox" searchable>
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
                        <flux:select label="Ano de expiração" placeholder="Insira seu numero aqui..." variant="listbox" searchable>

                            @for($i = $anoAtual; $i < $anoAtual + 60; $i++)

                                <flux:select.option value="{{$i}}">{{$i}}</flux:select.option>
                            @endfor

                        </flux:select>

                        <flux:input label="CCV" placeholder="Código de segurança do cartão..."/>

                    </div>

                @elseif($formaPagamento == FormaPagamentoContratacaoEnum::BOLETO->value)
                    <flux:card size="sm" class="hover:bg-zinc-50 dark:hover:bg-zinc-700 flex items-center justify-center">
                        <flux:heading class="flex items-center gap-2" size="xl">
                            Clique no botão <span class="text-accent">"CONTRATAR"</span> abaixo para gerar seu boleto bancário.
                        </flux:heading>

                    </flux:card>
                @endif
                <flux:separator></flux:separator>
                <flux:button variant="success">Contratar</flux:button>
            </div>
        </flux:fieldset>


    </form>
</div>
