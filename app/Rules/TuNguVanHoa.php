<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TuNguVanHoa implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //

    }
    public function passes($attribute,$value ) {
        if (str_contains($value, 'mày')) {
            return false;
        }
        return true;
    }
    public function messages() {
        return "tu ngu khong hop le";
    }

}
