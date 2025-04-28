<?php

declare(strict_types = 1);

namespace App\Http\Requests;

abstract class BaseValidationRequest
{
    public function __construct(private readonly array $attributes = [])
    {
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    abstract public function prepareForValidation(): void;

    abstract public function rules(): array;

    abstract public function messages(): array;

    abstract public function validated(): array;
}
