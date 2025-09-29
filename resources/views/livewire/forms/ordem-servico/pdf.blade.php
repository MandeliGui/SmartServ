<?php

use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Livewire\Forms\OrdemServicoForm;
use App\Models\OrdemServicoModel;
use App\Services\Tenant\OrdemServicoService;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public OrdemServicoForm $form;

    public $ordemServico;
    public $user;

    public function mount($ordemServico)
    {
        $this->ordemServico = $ordemServico;
        $this->user = auth()->user();

    }


}; ?>

<div>

    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Ordem de Serviço</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 0;
                padding: 10px;
            }

            .via {
                width: 100%;
                border: 1px solid #000;
                padding: 10px;
                margin-bottom: 20px;
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #000;
                padding-bottom: 5px;
                margin-bottom: 5px;
            }

            .empresa {
                font-weight: bold;
                font-size: 14px;
            }

            .dados {
                margin-bottom: 8px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 8px;
            }

            th, td {
                border: 1px solid #000;
                padding: 4px;
                text-align: left;
            }

            th:nth-child(1), td:nth-child(1) {
                width: 50%;
            }

            th:nth-child(2), td:nth-child(2) {
                width: 10%;
            }

            th:nth-child(3), td:nth-child(3) {
                width: 20%;
            }

            th:nth-child(4), td:nth-child(4) {
                width: 20%;
            }

            .total {
                text-align: right;
                font-weight: bold;
            }

            .assinatura {
                margin-top: 20px;
                text-align: center;
            }

            .assinatura span {
                display: inline-block;
                border-top: 1px solid #000;
                width: 200px;
            }

            .observacao {
                font-size: 10px;
                margin-top: 5px;
                text-align: center;
            }
        </style>
    </head>
    <body>

    <!-- VIA EMPRESA -->
    <div class="via">
        <div class="header">
            <div class="empresa">
                {{$user->name}}<br>
                CNPJ: {{'CNPJ DO USUARIO AQUI'}}
            </div>
            <div>
                <br>
                <h2 style="text-align: center;"><strong>Ordem de Serviço nº {{$ordemServico->codigo}}</strong></h2>
                {{--                Data: 22/09/2025--}}
            </div>
        </div>

        <div class="dados">
            {{--            @dd($ordemServico->cliente->pessoa)--}}
            <strong>Cliente:</strong> {{$ordemServico->cliente->pessoa->nomeFantasia ?? $ordemServico->cliente->pessoa->nomeRazaoSocial}}<br>
            Endereço: {{Helper::obterEnderecoPorExtenso($ordemServico->cliente->pessoa->endereco)}}<br>
            Telefone: {{Helper::formatarPhoneBR($ordemServico->cliente->pessoa->telefone)}}<br>
            CPF/CNPJ: {{Helper::formatarCpfCnpj($ordemServico->cliente->pessoa->cpfCnpj)}}
        </div>
        @if($ordemServico->materiais->count() > 0)

            <table>
                <colgroup>
                    <col style="width: 55%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                </colgroup>
                <thead>
                <tr>
                    <th>Material</th>
                    <th>Qtd</th>
                    <th>Valor Unit.</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $valorTotal = 0;
                @endphp
                @foreach($ordemServico->materiais as $material)

                    {{--            @dump($servico)--}}
                    <tr>
                        <td>{{$material->nome}}</td>
                        <td>{{$quantidade = $material->pivot->quantidade}}</td>
                        <td>R$ {{$valorUnitario = $material->valor}}</td>
                        <td>R$ {{Helper::formatarValorMonetarioPtBr( $valorTotal += $quantidade * $valorUnitario)}}</td>
                    </tr>

                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" class="total">Total</td>
                    <td><strong>R$ {{Helper::formatarValorMonetarioPtBr($valorTotal)}}</strong></td>
                </tr>
                </tfoot>
            </table>
            <br>
        @endif
        @if($ordemServico->servicos->count() > 0)
            <table>
                <colgroup>
                    <col style="width: 55%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                </colgroup>
                <thead>
                <tr>
                    <th>Serviço</th>
                    <th>Qtd</th>
                    <th>Valor Unit.</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $valorTotal = 0;
                @endphp
                @foreach($ordemServico->servicos as $servico)

                    {{--            @dump($servico)--}}
                    <tr>
                        <td>{{$servico->nome}}</td>
                        <td>{{$quantidade = $servico->pivot->quantidade}}</td>
                        <td>R$ {{$valorUnitario = $servico->valor}}</td>
                        <td>R$ {{Helper::formatarValorMonetarioPtBr( $valorTotal += $quantidade * $valorUnitario)}}</td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" class="total">Total</td>
                    <td><strong>R$ {{Helper::formatarValorMonetarioPtBr($valorTotal)}}</strong></td>
                </tr>
                </tfoot>
            </table>
        @endif
        <br>
        <br>

        <div class="assinatura">
            <span>Assinatura do Cliente</span>
        </div>

        <div class="observacao">
            Este documento não é válido como nota fiscal.
        </div>
    </div>

    <!-- VIA CLIENTE -->
    <div class="via">
        <div class="header">
            <div class="empresa">
                {{$user->name}}<br>
                CNPJ: {{'CNPJ DO USUARIO AQUI'}}
            </div>
            <div>
                <br>
                <h2 style="text-align: center;"><strong>Ordem de Serviço nº {{$ordemServico->codigo}}</strong></h2>
                {{--                Data: 22/09/2025--}}
            </div>
        </div>

        <div class="dados">
            {{--            @dd($ordemServico->cliente->pessoa)--}}
            <strong>Cliente:</strong> {{$ordemServico->cliente->pessoa->nomeFantasia ?? $ordemServico->cliente->pessoa->nomeRazaoSocial}}<br>
            Endereço: {{Helper::obterEnderecoPorExtenso($ordemServico->cliente->pessoa->endereco)}}<br>
            Telefone: {{Helper::formatarPhoneBR($ordemServico->cliente->pessoa->telefone)}}
        </div>
        @if($ordemServico->materiais->count() > 0)

            <table>
                <thead>
                <tr>
                    <th>Material</th>
                    <th>Qtd</th>
                    <th>Valor Unit.</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $valorTotal = 0;
                @endphp
                @foreach($ordemServico->materiais as $material)

                    {{--            @dump($servico)--}}
                    <tr>
                        <td>{{$material->nome}}</td>
                        <td>{{$quantidade = $material->pivot->quantidade}}</td>
                        <td>R$ {{$valorUnitario = $material->valor}}</td>
                        <td>R$ {{Helper::formatarValorMonetarioPtBr( $valorTotal += $quantidade * $valorUnitario)}}</td>
                    </tr>

                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" class="total">Total</td>
                    <td><strong>R$ {{Helper::formatarValorMonetarioPtBr($valorTotal)}}</strong></td>
                </tr>
                </tfoot>
            </table>
            <br>
        @endif
        @if($ordemServico->servicos->count() > 0)
            <table>
                <thead>
                <tr>
                    <th>Serviço</th>
                    <th>Qtd</th>
                    <th>Valor Unit.</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $valorTotal = 0;
                @endphp
                @foreach($ordemServico->servicos as $servico)

                    {{--            @dump($servico)--}}
                    <tr>
                        <td>{{$servico->nome}}</td>
                        <td>{{$quantidade = $servico->pivot->quantidade}}</td>
                        <td>R$ {{$valorUnitario = $servico->valor}}</td>
                        <td>R$ {{Helper::formatarValorMonetarioPtBr( $valorTotal += $quantidade * $valorUnitario)}}</td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" class="total">Total</td>
                    <td><strong>R$ {{Helper::formatarValorMonetarioPtBr($valorTotal)}}</strong></td>
                </tr>
                </tfoot>
            </table>
        @endif
        <br>
        <br>

        <div class="assinatura">
            <span>Assinatura do Cliente</span>
        </div>

        <div class="observacao">
            Este documento não é válido como nota fiscal.
        </div>
    </div>

    </body>
    </html>
</div>
