<?php

namespace App\Livewire\Forms;

use App\Services\Tenant\MateriaisService;
use Livewire\Form;

class AdicionarMateriaisForm extends Form
{
    public const MODAL_NAME_SELECIONAR_MATERIAL            = 'modal-ordem-servico-selecionar-material';
    public const PATH_COMPONENT_FORM_SELECIONAR_MATERIAL   = 'forms.ordem-servico.selecionar-material';
    public const EVENT_NAME_SHOW_MODAL_SELECIONAR_MATERIAL = 'show-modal-selecionar-material';

    public const EVENT_PERSISTED = 'adicionar-materiais-persisted';

    public ?int    $id_material   = null;
    public ?string $codigo        = null;
    public ?string $quantidade    = null;
    public ?string $valorUnitario = null;
    public ?string $valorTotal    = null;

    private function attributes(): array
    {
        return [
            "id_material"   => "Id",
            "codigo"        => "Material",
            "quantidade"    => "Quantidade",
            "valorUnitario" => "Valor Unitário",
            "valorTotal"    => "Valor Total",
        ];
    }


    public function getMaterialById(string $id)
    {
        return (new MateriaisService())->findOne($id);
    }

    public function setMateriais(string $id): void
    {
        $material = (new MateriaisService())->findOne($id);

        $this->id_material = $material->id;
    }
}
