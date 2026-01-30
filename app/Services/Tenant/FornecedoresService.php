<?php

namespace App\Services\Tenant;

use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\EnderecoModel;
use App\Models\FornecedoresModel;

class FornecedoresService
{
    private readonly FornecedoresModel $fornecedor;

    private readonly EnderecoModel $endereco;


    public function __construct()
    {
        $this->fornecedor = new FornecedoresModel();
        $this->endereco   = new EnderecoModel();
    }

    public function findAll(FilterPaginateRequest $request, array $filters = [])
    {
        $request->validate(
            rules: [
                "orderBy" => ["in:nome_fantasia"],
            ],
            messages: [
                "orderBy.in" => "não e possível ordernar pela coluna :attribute",
            ]
        );

        return $this->fornecedor::query()
//            ->when(is_null($request->search), function ($query) {
//                $query->where("removido", "=", false);
//            })
//            ->search($request->search)
                                ->when(!is_null($request->orderBy), function ($query) use ($request): void {
                $query->orderBy($request->orderBy, $request->dir);
            })->paginate(perPage: $request->limit, page: $request->offset);
    }

    public function findOne(mixed $id)
    {
        return $this->fornecedor::query()->where('id', $id)->first();
    }

    public function create(array $data)
    {

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

        $pessoa = $this->fornecedor::query()->create([
            "razao_social"  => $data["nomeRazaoSocial"],
            "nome_fantasia" => $data['nomeFantasia'],
            "telefone"      => $data['telefone'],
            "atendente"     => $data['atendente'],
            "cnpj"          => $data['cnpj'],
            "email"         => $data['email'],
            "id_endereco"   => $endereco->id,
            "user_id"       => auth()->user()->id,

        ]);


    }

    public function update(array $data)
    {
        $fornecedor = $this->fornecedor::query()->where("id", $data["id"])->first();
        dd($fornecedor);
        $endereco = $this->endereco::query()->where("id", $fornecedor->id_endereco)->first();

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

        $fornecedor->update([
            "razao_social"  => $data["nomeRazaoSocial"],
            "nome_fantasia" => $data['nomeFantasia'],
            "telefone"      => $data['telefone'],
            "atendente"     => $data['atendente'],
            "cnpj"          => $data['cnpj'],
            "email"         => $data['email'],
            "id_endereco"   => $endereco->id,
            "user_id"       => auth()->user()->id,
        ]);


        return $fornecedor;
    }

    public function remove(mixed $id)
    {
        $fornecedor = $this->fornecedor::query()->where("id", $id)->first();

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
            $fornecedor->delete();
            $fornecedor->endereco->delete();
        }

        return $fornecedor;
    }
}

