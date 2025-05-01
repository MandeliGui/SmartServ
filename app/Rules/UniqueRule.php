<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueRule implements ValidationRule
{
    public function __construct(string $table, string $column, ?int $id = null)
    {
        $this->table  = $table;
        $this->column = $column;
        $this->id     = $id;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $exists = DB::table($this->table)
            ->where($this->column, $value)
            ->where('user_id', auth()->id())
            ->when($this->id, function ($query) {
                $query->where('id', '!=', $this->id);
            })
            ->exists();

        if ($exists) {
            $fail("O valor do campo {$attribute} já está em uso.");
        }

    }
}
