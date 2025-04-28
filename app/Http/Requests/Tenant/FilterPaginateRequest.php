<?php

declare(strict_types = 1);

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class FilterPaginateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'limit'   => ['nullable', 'integer', 'min:1'],
            'offset'  => ['nullable', 'integer', 'min:0'],
            'orderBy' => ['nullable', 'string'],
            'dir'     => ['nullable', 'string', 'in:asc,desc'],
            'search'  => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'limit.integer'  => ':attribute deve ser um número inteiro',
            'limit.min'      => ':attribute deve ser no mínimo 1',
            'offset.integer' => ':attribute deve ser um número inteiro',
            'offset.min'     => ':attribute deve ser no mínimo 0',
            'orderBy.string' => ':attribute deve ser uma string',
            'dir.string'     => ':attribute deve ser uma string',
            'dir.in'         => ':attribute deve ser: asc ou desc',
            'search.string'  => ':attribute deve ser uma string',
        ];
    }

    public function passedValidation(): void
    {
        $this->merge([
            'limit'  => $this->limit ?? 10,
            'offset' => $this->offset,
            'dir'    => $this->dir ?? 'asc',
        ]);
    }
}
