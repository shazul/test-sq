<?php

namespace Pimeo\Services\Validators\Contracts;

interface ValidatorContract
{
    /**
     * Validate a value.
     *
     * @param string $attribute
     * @param mixed  $value
     * @param array  $parameters
     * @return boolean
     */
    public function validate($attribute, $value, $parameters);
}
