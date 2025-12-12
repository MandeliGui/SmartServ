<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Enums\Admin\Planos;
use App\Enums\TipoPessoaEnum;
use App\Helpers\Helper;
use App\Http\Requests\Tenant\AuthRequest;
use App\Http\Requests\Tenant\TecnicoRequest;
use App\Models\Adm\PlanosModel;
use App\Models\Adm\UserPlano;
use App\Models\User;
use App\Services\ExternalApi\Asaas\Facades\Asaas;
use App\Services\Tenant\TecnicoService;
use Carbon\Carbon;
use Flux\Flux;
use Livewire\Form;

class RegisterForm extends Form
{


    public ?string $tipoPessoa = 'PJ';
    public ?string $nomeCliente;
    public ?string $telefone;
    public ?string $email;
    public ?string $cpf;

    public ?string $cnpj;
    public ?string $razaoSocial;
    public ?string $nomeFantasia;
    public ?string $tipoEmpresa;


    public string  $formaPagamento = "CREDIT_CARD";
    public ?string $anoAtual;


    public array $endereco = [
        'cep'         => null,
        'rua'         => null,
        'numero'      => null,
        'bairro'      => null,
        'complemento' => null,
        'cidade'      => null,
        'uf'          => null,
    ];

    public array $dadosCartao = [
        'nomeImpresso' => null,
        'numeroCartao' => null,
        'mesExpiracao' => null,
        'anoExpiracao' => null,
        'ccv'          => null,
    ];

    private function attributes(): array
    {
        return [
            'Nome'                 => 'Nome',
            'telefone'             => 'Telefone',
            'email'                => 'Email',
            'cpf'                  => 'CPF',
            'dataNascimento'       => 'Data de nascimento',
            'endereco.cep'         => 'CEP',
            'endereco.rua'         => 'Rua',
            'endereco.numero'      => 'Número',
            'endereco.bairro'      => 'Bairro',
            'endereco.complemento' => 'Complemento',
            'endereco.cidade'      => 'Cidade',
            'endereco.uf'          => 'UF',
        ];
    }


    public function create(): void
    {
        $data = AuthRequest::create($this->all())->validated();
//        dd($data['tipoPessoa']);

        try {
            \DB::beginTransaction();

            $cpfCnpj = $data['tipoPessoa'] === 'PF' ? $data['cpf'] : $data['cnpj'];


            $customer = Asaas::customers()->post([
                'name'    => $data['nomeCliente'],
                'cpfCnpj' => $cpfCnpj,
            ]);


            $planoPadrao = PlanosModel::where('id', Planos::PADRAO->value)->first();

            $body = [
                'customer'          => $customer->id,
                'billingType'       => $data['formaPagamento'],
                'nextDueDate'       => now()->addDays(7)->format('Y-m-d'),
                'value'             => $planoPadrao->valor,
                'description'       => 'Assinatura Mensal',
                'externalReference' => uniqid('sub_'),
            ];


            $subscription = Asaas::subscription()->post($body);

            $user = User::create([
                'name'          => $data['nomeCliente'],
                'razao_social'  => $data['razaoSocial'] ?? null,
                'nome_fantasia' => $data['nomeFantasia'] ?? null,
                'email'         => $data['email'],
                'password'      => password_hash('123456', PASSWORD_DEFAULT),
                'cpf_cnpj'      => $data['tipoPessoa'] === TipoPessoaEnum::PESSOA_FISICA->value ? $data['cpf'] : $data['cnpj'],
                'telefone'      => $data['telefone'],
            ]);


            $planoUsuario = UserPlano::create([
                'user_id'  => $user->id,
                'plano_id' => $planoPadrao->id,
            ]);




            Flux::toast('Usuário registrado com sucesso!', variant: 'success');
            \DB::commit();


        } catch (\Throwable $e) {
            Flux::toast($e->getMessage(), 'error');
            \DB::rollBack();
        }


    }
}
