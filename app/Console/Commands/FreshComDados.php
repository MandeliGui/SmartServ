<?php

namespace App\Console\Commands;

use App\Models\AtendenteModel;
use App\Models\CategoriaEntradaSaidaModel;
use App\Models\ClienteModel;
use App\Models\EnderecoModel;
use App\Models\EntradasSaidasModel;
use App\Models\FormaPagamentoModel;
use App\Models\GrupoClienteModel;
use App\Models\MaterialModel;
use App\Models\OrdemServicoModel;
use App\Models\PessoaModel;
use App\Models\ServicosModel;
use App\Models\TecnicoModel;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Console\Command;

class FreshComDados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users                 = User::all();
        $enderecos             = EnderecoModel::all();
        $pessoas               = PessoaModel::all();
        $grupoClientes         = GrupoClienteModel::all();
        $clientes              = ClienteModel::all();
        $usuarios              = Usuario::all();
        $servicos              = ServicosModel::all();
        $tecnicos              = TecnicoModel::all();
        $formasPagamento       = FormaPagamentoModel::all();
        $materiais             = MaterialModel::all();
        $atendentes            = AtendenteModel::all();
        $ordensServicos        = OrdemServicoModel::all();
        $materiaisOrdemServico = [];
        foreach ($ordensServicos as $ordemServico) {
            foreach ($ordemServico->materiais as $material) {

                $materiaisOrdemServico[] = [
                    "idOrdemServico" => $material->pivot['idOrdemServico'],
                    "idMaterial"     => $material->pivot['idMaterial'],
                    "id"             => $material->pivot['id'],
                    "quantidade"     => $material->pivot['quantidade'],
                    "valorUnitario"  => $material->pivot['valorUnitario'],
                    "valorTotal"     => $material->pivot['valorTotal'],
                ];

            }
        }
        $servicosOrdemServico = [];
        foreach ($ordensServicos as $ordemServico) {

            foreach ($ordemServico->servicos as $servico) {
                $servicosOrdemServico[] = [
                    "idOrdemServico" => $servico->pivot['idOrdemServico'],
                    "idServico"      => $servico->pivot['idServico'],
                    "id"             => $servico->pivot['id'],
                    "quantidade"     => $servico->pivot['quantidade'],
                    "valorUnitario"  => $servico->pivot['valorUnitario'],
                    "valorTotal"     => $servico->pivot['valorTotal'],
                ];
            }
        }
        $categoriasEntradasSaidas = CategoriaEntradaSaidaModel::all();
        $entradasSaidas           = EntradasSaidasModel::all();
        $this->call('migrate:fresh');

        foreach ($users as $user) {
            User::create([
                'name'     => $user->name,
                'email'    => $user->email,
                'password' => $user->password,
            ]);
        }
        foreach ($enderecos as $endereco) {
            ds()->queriesOn();
            EnderecoModel::create([
                'cep'         => $endereco->cep,
                'rua'         => $endereco->rua,
                'numero'      => $endereco->numero,
                'complemento' => $endereco->complemento,
                'bairro'      => $endereco->bairro,
                'cidade'      => $endereco->cidade,
                'uf'          => $endereco->uf,
                "user_id"     => $endereco->user_id,
            ]);
            ds()->queriesOff();
        }
        foreach ($pessoas as $pessoa) {
            PessoaModel::create([
                'nomeRazaoSocial' => $pessoa->nomeRazaoSocial,
                'nomeFantasia'    => $pessoa->nomeFantasia,
                'telefone'        => $pessoa->telefone,
                'cpfCnpj'         => $pessoa->cpfCnpj,
                'email'           => $pessoa->email,
                'dataNascimento'  => $pessoa->dataNascimento,
                'tipoPessoa'      => $pessoa->tipoPessoa,
                'idEndereco'      => $pessoa->idEndereco,
                'user_id'         => $pessoa->user_id,
                'removido'        => $pessoa->removido,
            ]);
        }
        foreach ($grupoClientes as $grupoCliente) {
            GrupoClienteModel::create([
                'nome'     => $grupoCliente->nome,
                'user_id'  => $grupoCliente->user_id,
                'removido' => $grupoCliente->removido,
            ]);
        }
        foreach ($clientes as $cliente) {
            ClienteModel::create([
                'idCliente' => $cliente->idCliente,
                'idGrupo'   => $cliente->idGrupo,
                'user_id'   => $cliente->user_id,
            ]);
        }
        foreach ($usuarios as $usuario) {
            Usuario::create([
                'nome'     => $usuario->nome,
                'email'    => $usuario->email,
                'password' => $usuario->password,
                'removido' => $usuario->removido,
                'user_id'  => $usuario->user_id
            ]);
        }
        foreach ($servicos as $servico) {
            ServicosModel::create([
                'codigo'    => $servico->codigo,
                'nome'      => $servico->nome,
                'descricao' => $servico->descricao,
                'valor'     => $servico->valor,
                'user_id'   => $servico->user_id,
            ]);
        }
        foreach ($tecnicos as $tecnico) {
            TecnicoModel::create([
                'idTecnico' => $tecnico->idTecnico,
                'user_id'   => $tecnico->user_id,
                'removido'  => $tecnico->removido,
            ]);
        }
        foreach ($formasPagamento as $formaPagamento) {
            FormaPagamentoModel::create([
                'nome'      => $formaPagamento->nome,
                'descricao' => $formaPagamento->descricao,
                'user_id'   => $formaPagamento->user_id,
                'removido'  => $formaPagamento->removido,
            ]);
        }
        foreach ($materiais as $material) {
            MaterialModel::create([
                'codigo'    => $material->codigo,
                'nome'      => $material->nome,
                'descricao' => $material->descricao,
                'unidade'   => $material->unidade,
                'valor'     => $material->valor,
                'user_id'   => $material->user_id,
            ]);
        }
        foreach ($atendentes as $atendente) {
            AtendenteModel::create([
                'idAtendente' => $atendente->idAtendente,
                'user_id'     => $atendente->user_id,
                'removido'    => $atendente->removido,
            ]);
        }
        foreach ($ordensServicos as $ordemServico) {
            OrdemServicoModel::create([
                'codigo'       => $ordemServico->codigo,
                'tipo'         => $ordemServico->tipo,
                'dataAbertura' => $ordemServico->dataAbertura,
                'dataEntrega'  => $ordemServico->dataEntrega,
                'status'       => $ordemServico->status,
                'valorTotal'   => $ordemServico->valorTotal,
                'idCliente'    => $ordemServico->idCliente,
                'idTecnico'    => $ordemServico->idTecnico,
                'idAtendente'  => $ordemServico->idAtendente,
                'user_id'      => $ordemServico->user_id,
                'removido'     => $ordemServico->removido,

            ]);
        }
        foreach ($materiaisOrdemServico as $materialOrdemServico) {
            OrdemServicoModel::find($materialOrdemServico['idOrdemServico'])
                ->materiais()
                ->attach($materialOrdemServico['idMaterial'], [
                    'id'            => $materialOrdemServico['id'],
                    'quantidade'    => $materialOrdemServico['quantidade'],
                    'valorUnitario' => $materialOrdemServico['valorUnitario'],
                    'valorTotal'    => $materialOrdemServico['valorTotal'],
                ]);
        }
        foreach ($servicosOrdemServico as $servicoOrdemServico) {
            OrdemServicoModel::find($servicoOrdemServico['idOrdemServico'])
                ->servicos()
                ->attach($servicoOrdemServico['idServico'], [
                    'id'            => $servicoOrdemServico['id'],
                    'quantidade'    => $servicoOrdemServico['quantidade'],
                    'valorUnitario' => $servicoOrdemServico['valorUnitario'],
                    'valorTotal'    => $servicoOrdemServico['valorTotal'],
                ]);
        }
        foreach ($categoriasEntradasSaidas as $categoria) {
            CategoriaEntradaSaidaModel::create([
                'nome'      => $categoria->nome,
                'tipo'      => $categoria->tipo,
                'descricao' => $categoria->descricao,
                'removido'  => $categoria->removido,
                'user_id'   => $categoria->user_id,
            ]);
        }
        foreach ($entradasSaidas as $entradaSaida) {
            EntradasSaidasModel::create([
                'tipo'               => $entradaSaida->tipo,
                'data_vencimento'    => $entradaSaida->data_vencimento,
                'data_pagamento'     => $entradaSaida->data_pagamento,
                'valor_original'     => $entradaSaida->valor_original,
                'valor_pago'         => $entradaSaida->valor_pago,
                'quantidade_meses'   => $entradaSaida->quantidade_meses,
                'descricao'          => $entradaSaida->descricao,
                'categoria_id'       => $entradaSaida->categoria_id,
                'forma_pagamento_id' => $entradaSaida->forma_pagamento_id,
                'removido'           => $entradaSaida->removido,
                'user_id'            => $entradaSaida->user_id,
            ]);
        }

    }
}
