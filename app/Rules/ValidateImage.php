<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateImage implements Rule
{
    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !$value || is_array(@getimagesize($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.invalid');
    }
}
