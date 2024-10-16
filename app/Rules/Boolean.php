<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Boolean implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail) :void
    {
            if (!is_bool(to_boolean($value))) {
                $fail('Значение должно быть булево типа');
            }
    }
}
