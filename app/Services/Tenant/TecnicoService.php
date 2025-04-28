<?php

declare(strict_types = 1);

namespace App\Services\Tenant;

use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\EnderecoModel;
use App\Models\PessoaModel;
use App\Models\TecnicoModel;

class TecnicoService
{
    private readonly PessoaModel   $pessoa;

    private readonly EnderecoModel $endereco;

    private readonly TecnicoModel  $tecnico;

    public function __construct()
    {
        $this->pessoa   = new PessoaModel();
        $this->endereco = new EnderecoModel();
        $this->tecnico  = new TecnicoModel();
    }

    public function findAll(FilterPaginateRequest $request, array $filters = [])
    {
        $request->validate(
            rules: [
                "orderBy" => ["in:nomeRazaoSocial"],
            ],
            messages: [
                "orderBy.in" => "não e possível ordernar pela coluna :attribute",
            ]
        );

        return $this->pessoa::query()
//            ->when(is_null($request->search), function ($query) {
//                $query->where("removido", "=", false);
//            })
//            ->search($request->search)
            ->when(! is_null($request->orderBy), function ($query) use ($request): void {
                $query->orderBy($request->orderBy, $request->dir);
            })
            ->whereHas('tecnico')
            ->paginate(perPage: $request->limit, page: $request->offset);
    }

    public function findOne(mixed $id)
    {
        return $this->tecnico::query()->where('idTecnico', $id)->first();
    }

    public function create(array $data)
    {
        $existePessoa = $this->pessoa::query()->where("cpfCnpj", $data["cpf"])->first();

        if (! $existePessoa) {
            $endereco = $this->endereco::query()->create([
                "cep"         => $data['endereco']["cep"],
                "rua"         => $data['endereco']["rua"],
                "numero"      => $data['endereco']["numero"],
                "bairro"      => $data['endereco']["bairro"],
                "complemento" => $data['endereco']["complemento"],
                "cidade"      => $data['endereco']["cidade"],
                "uf"          => $data['endereco']["uf"],
                "user_id"     => auth()->user()->id,
            ]);
            $endereco->refresh();

            $pessoa = $this->pessoa::query()->create([
                "nomeRazaoSocial" => $data["nome"],
                "telefone"        => $data['telefone'],
                "cpfCnpj"         => $data['cpf'],
                "email"           => $data['email'],
                "dataNascimento"  => $data['dataNascimento'],
                "idEndereco"      => $endereco->id,
                "user_id"         => auth()->user()->id,

            ]);
        } else {
            $pessoa = $existePessoa;
        }

        $existeTecnico = $this->tecnico::query()->where("idTecnico", $pessoa->id)->first();

        if ($existeTecnico) {
            return null;
        }

        return $this->tecnico::query()->create([
            "idTecnico" => $pessoa->id,
            "user_id"   => auth()->user()->id,

        ]);
    }

    public function update(array $data)
    {
        $tecnico = $this->tecnico::query()->where("idTecnico", $data["id"])->first();

        $endereco = $this->endereco::query()->where("id", $tecnico->pessoa->idEndereco)->first();

        $endereco->update([
            "cep"         => $data['endereco']["cep"],
            "rua"         => $data['endereco']["rua"],
            "numero"      => $data['endereco']["numero"],
            "bairro"      => $data['endereco']["bairro"],
            "complemento" => $data['endereco']["complemento"],
            "cidade"      => $data['endereco']["cidade"],
            "uf"          => $data['endereco']["uf"],
            "user_id"     => auth()->user()->id,
        ]);

        $endereco->refresh();

        $tecnico->pessoa->update([
            "nomeRazaoSocial" => $data["nome"],
            "telefone"        => $data['telefone'],
            "cpfCnpj"         => $data['cpf'],
            "email"           => $data['email'],
            "dataNascimento"  => $data['dataNascimento'],
            "idEndereco"      => $endereco->id,
            "user_id"         => auth()->user()->id,
        ]);

        return $tecnico;
    }

    public function remove(mixed $id)
    {
        $pessoa = $this->pessoa::query()->where("id", $id)->first();

        //TODO: VERIFICAR ONDE OS CLIENTES VAO ESTAR VINCULADOS E REMOVER LOGICAMENTE!

        if (false) {
            $pessoa->cliente->update([
                "removido" => true,
                "user_id"  => auth()->user()->id,
            ]);
            $pessoa->endereco->update([
                "removido" => true,
                "user_id"  => auth()->user()->id,
            ]);
            $pessoa->update([
                "removido" => true,
                "user_id"  => auth()->user()->id,
            ]);
        } else {
            $pessoa->tecnico->delete();
            $pessoa->delete();
            $pessoa->endereco->delete();
        }

        return $pessoa;
    }
}
