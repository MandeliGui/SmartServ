<?php

declare(strict_types = 1);

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\TecnicoRequest;
use App\Services\Tenant\TecnicoService;
use Carbon\Carbon;
use Livewire\Form;

class TecnicoForm extends Form
{
    public const MODAL_NAME_REMOVE                     = 'modal-tecnico-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-tecnico-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.tecnicos.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.tecnico.remove';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-tecnico';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-tecnico-remove-multiple';
    public const EVENT_PERSISTED                       = 'tecnico-persisted';

    public array $selectedsIds = [];

    public ?int    $id = null;

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
        $this->dataNascimento = $this->dataNascimento !== null && $this->dataNascimento !== '' && $this->dataNascimento !== '0' ? Carbon::createFromFormat('d/m/Y', $this->dataNascimento)->format('Y-m-d') : null;
        $data                 = TecnicoRequest::create($this->all(), $this->attributes())->validated();
        (new TecnicoService())->create($data);
        redirect(route('tecnico'));
    }

    public function update(): void
    {
        $this->dataNascimento = $this->dataNascimento !== null && $this->dataNascimento !== '' && $this->dataNascimento !== '0' ? Carbon::createFromFormat('d/m/Y', $this->dataNascimento)->format('Y-m-d') : null;
        $data                 = TecnicoRequest::update($this->all(), $this->attributes())->validated();
        (new TecnicoService())->update($data);
        redirect(route('tecnico'));
    }

    public function remove(): void
    {
        try {
            $data = TecnicoRequest::remove($this->id)->validated();

            (new TecnicoService())->remove($data["id"]);

            $this->reset();
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    public function setTecnico(mixed $id): void
    {
        $tecnico = (new TecnicoService())->findOne($id);

        $this->id             = $tecnico->idTecnico;
        $this->nome           = $tecnico->pessoa->nomeRazaoSocial;
        $this->telefone       = $tecnico->pessoa->telefone;
        $this->cpf            = $tecnico->pessoa->cpfCnpj;
        $this->dataNascimento = $tecnico->pessoa->dataNascimento ? Carbon::parse($tecnico->pessoa->dataNascimento)->format('d-m-Y') : null;
        $this->email          = $tecnico->pessoa->email;

        $this->endereco = [
            'cep'         => $tecnico->pessoa->endereco->cep,
            'rua'         => $tecnico->pessoa->endereco->rua,
            'numero'      => $tecnico->pessoa->endereco->numero,
            'bairro'      => $tecnico->pessoa->endereco->bairro,
            'complemento' => $tecnico->pessoa->endereco->complemento,
            'cidade'      => $tecnico->pessoa->endereco->cidade,
            'uf'          => $tecnico->pessoa->endereco->uf,
        ];
    }
}
