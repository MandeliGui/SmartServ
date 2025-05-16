<?php

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\AtendenteRequest;
use App\Services\Tenant\AtendenteService;
use Carbon\Carbon;
use Flux\Flux;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AtendenteForm extends Form

{
    public const MODAL_NAME_REMOVE                     = 'modal-atendente-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-atendente-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.atendentes.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.atendentes.remove';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-atendente';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-atendente-remove-multiple';
    public const EVENT_PERSISTED                       = 'atendente-persisted';

    public array $selectedsIds = [];

    public ?int $id = null;

    public ?string $nome = null;

    public ?string $telefone = null;

    public ?string $cpf = null;

    public ?string $dataNascimento = null;

    public ?string $email = null;

    public array $endereco = [
        'cep'         => null,
        'rua'         => null,
        'numero'      => null,
        'bairro'      => null,
        'complemento' => null,
        'cidade'      => null,
        'uf'          => null,
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
        $this->dataNascimento = $this->dataNascimento !== null && $this->dataNascimento !== '' && $this->dataNascimento !== '0' ? Carbon::createFromFormat('Y-m-d', $this->dataNascimento)->format('Y-m-d') : null;

        $data = AtendenteRequest::create($this->all(), $this->attributes())->validated();
        (new AtendenteService())->create($data);
        Flux::toast('Atendente criado com sucesso!', variant: 'success');
    }

    public function update(): void
    {
        $this->dataNascimento = $this->dataNascimento !== null && $this->dataNascimento !== '' && $this->dataNascimento !== '0' ? Carbon::createFromFormat('Y-m-d', $this->dataNascimento)->format('Y-m-d') : null;

        $data = AtendenteRequest::update($this->all(), $this->attributes())->validated();
        (new AtendenteService())->update($data);
        Flux::toast('Atendente atualizado com sucesso!', variant: 'success');
    }

    public function remove(): void
    {
        try {
            $data = AtendenteRequest::remove($this->id)->validated();

            (new AtendenteService())->remove($data["id"]);

            $this->reset();
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    public function setAtendente(mixed $id): void
    {
        $atendente = (new AtendenteService())->findOne($id);


        $this->id             = $atendente->idAtendente;
        $this->nome           = $atendente->pessoa->nomeRazaoSocial;
        $this->telefone       = $atendente->pessoa->telefone;
        $this->cpf            = $atendente->pessoa->cpfCnpj;
        $this->dataNascimento = $atendente->pessoa->dataNascimento ?? null;
        $this->email          = $atendente->pessoa->email;

        $this->endereco = [
            'cep'         => $atendente->pessoa->endereco->cep,
            'rua'         => $atendente->pessoa->endereco->rua,
            'numero'      => $atendente->pessoa->endereco->numero,
            'bairro'      => $atendente->pessoa->endereco->bairro,
            'complemento' => $atendente->pessoa->endereco->complemento,
            'cidade'      => $atendente->pessoa->endereco->cidade,
            'uf'          => $atendente->pessoa->endereco->uf,
        ];
    }
}
