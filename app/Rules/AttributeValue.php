<?php

namespace App\Rules;

use Closure;
use App\Entities\Shop\Attribute;
use Illuminate\Contracts\Validation\ValidationRule;


class AttributeValue implements ValidationRule
{

    private string $type;


    public function __construct($data)
    {
        $this->type = $data->request->get('type');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail):void
    {
        $values = array_map('trim', preg_split('#[\r\n]+#', $value));

        foreach ($values as $val) {
            if ($this->type === Attribute::TYPE_FLOAT && !filter_var($val, FILTER_VALIDATE_FLOAT)) {
                $fail('Значения должны быть типа float (число с плавающей точкой)');
            }
            if ($this->type === Attribute::TYPE_INTEGER && !filter_var($val, FILTER_VALIDATE_INT)) {
                $fail('Значения должны быть типа integer (цело число)');
            }
        }
    }
}
