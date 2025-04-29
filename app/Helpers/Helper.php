<?php

declare(strict_types = 1);

namespace App\Helpers;

use App\Enums\Persistence;
use Illuminate\Support\Str;
use stdClass;

class Helper
{
    public static function guard(): ?string
    {
        if (request()->is('api/*')) {
            return "api";
        }

        return null;
    }

    public static function getIdByRequest(array $data, string $key): mixed
    {
        return request()->route($key) ?? data_get($data, $key);
    }

    public static function getOnlyOrMultipleIdsByRequest(array &$data, $persistence, string $keyOneId = "id", string $keyMultipleIds = "ids"): bool
    {
        if ($persistence === Persistence::REMOVE_MULTIPLE) {
            $data[$keyMultipleIds] = data_get($data, $keyMultipleIds);

            return true;
        }

        if ($persistence === Persistence::REMOVE || $persistence === Persistence::FIND_ONE_BY_ID) {
            $data[$keyOneId] = self::getIdByRequest($data, $keyOneId);

            return true;
        }

        return false;
    }

    public static function getRulesOnlyIdOrMultipleIdsByRequest($persistence, string $table, string $column, string $keyOneId = "id", string $keyMultipleIds = "ids"): array
    {
        if ($persistence === Persistence::REMOVE || $persistence === Persistence::FIND_ONE_BY_ID) {
            return [
                $keyOneId => [
                    "required",
                    "integer",
                    "exists:{$table},{$column}",
                ],
            ];
        }

        if ($persistence === Persistence::REMOVE_MULTIPLE) {
            return [
                "{$keyMultipleIds}.*" => [
                    "required",
                    "integer",
                    "exists:{$table},{$column}",
                ],
            ];
        }

        return [];
    }

    public static function getMessagesOnlyIdOrMultipleIdsByRequest($persistence, string $keyOneId = "id", string $keyMultipleIds = "ids"): array
    {
        if ($persistence === Persistence::REMOVE || $persistence === Persistence::FIND_ONE_BY_ID) {
            return [
                $keyOneId . ".required" => ":attribute deve ser informado",
                $keyOneId . ".integer"  => ":attribute informado e invalido",
                $keyOneId . ".exists"   => ":attribute informado nao existe",
            ];
        }

        if ($persistence === Persistence::REMOVE_MULTIPLE) {
            return [
                "{$keyMultipleIds}.*.required" => ":attribute deve ser informado",
                "{$keyMultipleIds}.*.integer"  => ":attribute informado e invalido",
                "{$keyMultipleIds}.*.exists"   => ":attribute informado nao existe",
            ];
        }

        return [];
    }

    public static function formatarValorMonetarioPtBr(float $val): string
    {
        return number_format($val, 2, ",", ".");
    }

    public static function formatarDecimalDb(mixed $val): float
    {
        if (empty($val)) {
            return 0;
        }

        return (float)number_format(Str::replace([".", ","], ["", "."], $val), 2, ".", "");
    }

    public static function formatarValorMonetarioDB(mixed $val): float
    {
        return (float)number_format($val, 2, ".", "");
    }

    public static function calcularHorasExtra($salario, $totalHoras, $porcentagem): float
    {
        return ($salario / $totalHoras) * (1 + ($porcentagem / 100));
    }

    public static function formatarDataDB(?string $data): ?string
    {
        if ($data === null || $data === '' || $data === '0') {
            return null;
        }

        $ex = explode("/", $data);

        return $ex[2] . "-" . $ex[1] . "-" . $ex[0];
    }

    public static function formatarDataPtBr(?string $data): ?string
    {
        if ($data === null || $data === '' || $data === '0') {
            return null;
        }

        return date("d/m/Y", strtotime($data));
    }

    public static function formatarDateTimePtBr(?string $data): ?string
    {
        if ($data === null || $data === '' || $data === '0') {
            return null;
        }

        return date("d/m/Y H:i:s", strtotime($data));
    }

    public static function formatarPhoneBR(?string $phone): ?string
    {
        if ($phone === null || $phone === '' || $phone === '0') {
            return null;
        }

        if (strlen($phone) === 10) {
            return "(" . substr($phone, 0, 2) . ") " . substr($phone, 2, 4) . "-" . substr($phone, 6, 4);
        }

        return "(" . substr($phone, 0, 2) . ") " . substr($phone, 2, 5) . "-" . substr($phone, 7, 4);
    }

    public static function formatarCPF(string $cpf): string
    {
        if (strlen($cpf) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
        }

        return $cpf;
    }

    public static function formatarCNPJ(?string $cnpj): ?string
    {
        if (strlen((string) $cnpj) === 14) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', (string) $cnpj);
        }

        return $cnpj;
    }

    public static function formatarCpfCnpj(?string $cpfCnpj): ?string
    {
        if ($cpfCnpj === null || $cpfCnpj === '' || $cpfCnpj === '0') {
            return null;
        }

        $cpfCnpj = preg_replace('/[^0-9]/', '', $cpfCnpj);

        if (strlen((string) $cpfCnpj) === 11) {
            return self::formatarCPF($cpfCnpj);
        }

        if (strlen((string) $cpfCnpj) === 14) {
            return self::formatarCNPJ($cpfCnpj);
        }

        return $cpfCnpj;
    }

    public static function formatarCep(?string $cep): ?string
    {
        if ($cep === null || $cep === '' || $cep === '0') {
            return null;
        }

        if (strlen($cep) === 8) {
            return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
        }

        return $cep;
    }

    public static function obterTipoPessoaPorCpfCnpj(?string $cpfCnpj): ?string
    {
        if ($cpfCnpj === null || $cpfCnpj === '' || $cpfCnpj === '0') {
            return null;
        }

        $cpfCnpj = str()->replaceMatches('/[^0-9]/', '', $cpfCnpj);

        return strlen((string) $cpfCnpj) === 11 ? "PF" : (strlen((string) $cpfCnpj) === 14 ? "PJ" : null);
    }

    public static function obterEnderecoPorCep(?string $cep): ?stdClass
    {
        if ($cep === null || $cep === '' || $cep === '0') {
            return null;
        }

        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen((string) $cep) !== 8) {
            return null;
        }

        $url = "https://viacep.com.br/ws/{$cep}/json/";

        return json_decode(file_get_contents($url));
    }

    public static function obterDadosEmpresaPorCnpj(?string $cnpj): ?stdClass
    {

        if ($cnpj === null || $cnpj === '' || $cnpj === '0') {

            return null;
        }

        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen((string) $cnpj) !== 14) {
            return null;
        }


        $url = "https://open.cnpja.com/office/{$cnpj}";

        return json_decode(file_get_contents($url));
    }
}
