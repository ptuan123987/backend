<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProvinceDistricRule implements ValidationRule
{
    protected $province;
    public function __construct($province) {
        $this->province = $province;
    }

    public function passes($attribute, $value) {
        //  read provice to database, check xem trong province có district khong
    }
    public function message() {

    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
}
