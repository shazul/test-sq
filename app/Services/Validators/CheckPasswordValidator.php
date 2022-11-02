<?php

namespace Pimeo\Services\Validators;

use Hash;
use Pimeo\Services\Validators\Contracts\ValidatorContract;

class CheckPasswordValidator implements ValidatorContract
{
    /**
     * Validate allowed groups.
     *
     * @param string $attribute
     * @param mixed  $value
     * @param array  $parameters
     * @return boolean
     */
    public function validate($attribute, $value, $parameters)
    {
        return Hash::check($value, auth()->user()->password);
    }
}
