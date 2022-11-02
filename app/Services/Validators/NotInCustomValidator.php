<?php

namespace Pimeo\Services\Validators;

use Pimeo\Services\Validators\Contracts\ValidatorContract;

class NotInCustomValidator implements ValidatorContract
{
    /**
     * Validate an attribute is not contained within a list of values.
     *
     * @param string $attribute
     * @param mixed  $value
     * @param array  $parameters
     * @return boolean
     */
    public function validate($attribute, $value, $parameters)
    {
        return ! $this->validateInCustom($attribute, $value, $parameters);
    }

    /**
     * Validate in, without checking if the attribute has 'Array' rule.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    protected function validateInCustom($attribute, $value, $parameters)
    {
        if (is_array($value)) {
            while ($value) {
                list($key, $val) = each($value);
                is_array($val) ? $value[$key] = $val : $out[$key] = $val;
                break;
            }

            return count(array_diff($value, $parameters)) == 0;
        }

        return ! is_array($value) && in_array((string) $value, $parameters);
    }
}
