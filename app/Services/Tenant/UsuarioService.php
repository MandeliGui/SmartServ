<?php

namespace App\Services\Tenant;

use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\EnderecoModel;
use App\Models\PessoaModel;
use App\Models\Usuario;

class UsuarioService
{
    private readonly PessoaModel $pessoa;

    private readonly EnderecoModel $endereco;

    private readonly Usuario $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function findAll(FilterPaginateRequest $request, array $filters = [])
    {

        return $this->usuario->query()
            ->where("removido", "=", false)
            ->where('user_id', '=', auth()->user()->id)
            ->orderBy("nome")
            ->get();
    }
}
