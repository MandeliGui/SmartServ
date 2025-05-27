<?php

namespace App\Livewire\Forms;

use App\Services\Tenant\ServicosService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AdicionarServicosForm extends Form
{
    public const MODAL_NAME_SELECIONAR_SERVICO            = 'modal-ordem-servico-selecionar-servico';
    public const PATH_COMPONENT_FORM_SELECIONAR_SERVICO   = 'forms.ordem-servico.selecionar-servico';
    public const EVENT_NAME_SHOW_MODAL_SELECIONAR_SERVICO = 'show-modal-selecionar-servico';

    public const EVENT_PERSISTED = 'adicionar-servico-persisted';

    public ?int    $id_servico = null;
    public ?string $codigo     = null;
    public ?string $quantidade = null;

    private function attributes(): array
    {
        return [
            "id_servico" => "Id",
            "codigo"     => "Servico",
            "quantidade" => "Quantidade",
        ];
    }


    public function getServicoById(string $id)
    {
        return (new ServicosService())->findOne($id);
    }

    public function setServicos(string $id): void
    {
        $servico = (new ServicosService())->findOne($id);

        $this->id_servico = $servico->id;
    }
}
