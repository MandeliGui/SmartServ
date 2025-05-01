<?php

declare(strict_types = 1);

namespace App\Services\Tenant;

use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\ClienteModel;
use App\Models\EnderecoModel;
use App\Models\PessoaModel;

class ClienteService
{
    private readonly PessoaModel   $pessoa;

    private readonly EnderecoModel $endereco;

    private readonly ClienteModel  $cliente;

    public function __construct()
    {
        $this->pessoa   = new PessoaModel();
        $this->endereco = new EnderecoModel();
        $this->cliente  = new ClienteModel();
    }

    public function findAll(FilterPaginateRequest $request, array $filters = [])
    {
        $request->validate(
            rules: [
                "orderBy" => ["in:nomeFantasia"],
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
            ->whereHas('cliente')
            ->paginate(perPage: $request->limit, page: $request->offset);
    }

    public function findOne(mixed $id)
    {
        return $this->cliente::query()->where('idCliente', $id)->first();
    }

    public function create(array $data)
    {
        $existePessoa = $this->pessoa::query()->where("cpfCnpj", $data["cpfCnpj"])->first();


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
                "nomeRazaoSocial" => $data["nomeRazaoSocial"],
                "nomeFantasia"    => $data['nomeFantasia'],
                "telefone"        => $data['telefone'],
                "cpfCnpj"         => $data['cpfCnpj'],
                "email"           => $data['email'],
                "dataNascimento"  => $data['dataNascimento'],
                "idEndereco"      => $endereco->id,
                "user_id"         => auth()->user()->id,

            ]);
        } else {
            $pessoa = $existePessoa;
        }

        $existeCliente = $this->cliente::query()->where("idCliente", $pessoa->id)->first();

        if ($existeCliente) {
            return null;
        }

        return $this->cliente::query()->create([
            "idCliente" => $pessoa->id,
            "idGrupo"   => $data['idGrupo'],
            "user_id"   => auth()->user()->id,

        ]);
    }

    public function update(array $data)
    {
        $cliente = $this->cliente::query()->where("idCliente", $data["id"])->first();

        $endereco = $this->endereco::query()->where("id", $cliente->pessoa->idEndereco)->first();

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

        $cliente->pessoa->update([
            "nomeRazaoSocial" => $data["nomeRazaoSocial"],
            "nomeFantasia"    => $data['nomeFantasia'],
            "telefone"        => $data['telefone'],
            "cpfCnpj"         => $data['cpfCnpj'],
            "email"           => $data['email'],
            "dataNascimento"  => $data['dataNascimento'],
            "idEndereco"      => $endereco->id,
            "user_id"         => auth()->user()->id,
        ]);

        $cliente->update([
            "idGrupo" => $data['idGrupo'],
            "user_id" => auth()->user()->id,
        ]);

        return $cliente;
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
            $pessoa->cliente->delete();
            $pessoa->delete();
            $pessoa->endereco->delete();
        }

        return $pessoa;
    }

    public function removeMultiple(array $ids): void
    {
        foreach ($ids as $id) {
            $this->remove($id);
        }
    }
}
