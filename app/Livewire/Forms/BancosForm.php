<?php

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\BancoRequest;
use App\Services\Tenant\BancoService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BancosForm extends Form
{
    public const MODAL_NAME_CREATE                     = 'modal-banco-create';
    public const MODAL_NAME_UPDATE                     = 'modal-banco-update';
    public const MODAL_NAME_REMOVE                     = 'modal-banco-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-banco-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.bancos.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.bancos.remove';
    public const EVENT_NAME_SHOW_MODAL_CREATE          = 'show-modal-create-banco';
    public const EVENT_NAME_SHOW_MODAL_UPDATE          = 'show-modal-update-banco';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-banco';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-banco-remove-multiple';
    public const EVENT_PERSISTED                       = 'banco-persisted';


    public mixed   $id;
    public ?string $nome          = null;
    public ?string $saldo_inicial = null;
    public ?string $saldo         = null;


    private function attributes(): array
    {
        return [
            'nome'          => "Nome",
            'saldo_inicial' => "Saldo Inicial",
            'saldo'         => "Saldo Atual",
        ];
    }

    public function create()
    {
        $data = BancoRequest::create($this->all(), $this->attributes())->validated();

        $servico = (new BancoService())->create($data);

        $this->reset();

        return $servico;
    }

    public function update()
    {
        $data = BancoRequest::update($this->all(), $this->attributes())->validated();

        $servico = (new BancoService())->update($data, $this->id);

        $this->reset();

        return $servico;
    }

    public function remove()
    {
        $data = BancoRequest::remove($this->id, $this->attributes())->validated();

        $servico = (new BancoService())->remove($data);

        $this->reset();

        return $servico;
    }

    public function setBanco(mixed $id): void
    {
        $banco = (new BancoService())->findOne($id);

        if ($banco) {
            $this->id            = $banco->id;
            $this->nome          = $banco->nome;
            $this->saldo_inicial = $banco->saldo_inicial;
        } else {
            $this->id            = null;
            $this->nome          = null;
            $this->saldo_inicial = null;
        }
    }
}
