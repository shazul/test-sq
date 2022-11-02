<?php

namespace Pimeo\Repositories\Traits;

trait Decodable
{
    /**
     * @param  mixed $product
     * @return array
     */
    protected function decodeValues($product)
    {
        $decodedValue = json_decode($product->values, true);

        if (isset($product->attribute_values)) {
            $attr_values = [];

            $attributes_values = json_decode($product->attribute_values, true);

            if (empty($decodedValue['keys'])) {
                return $decodedValue;
            }

            foreach (get_current_company_languages() as $language) {
                foreach ((array) $decodedValue['keys'] as $value_key) {
                    $key = $attributes_values[$language->code][$value_key];
                    if (isset($key['name'])) {
                        $attr_values[$language->code][] = $key['name'];
                    } else {
                        $attr_values[$language->code][] = $key;
                    }
                }
                $decodedValue[$language->code] = implode(", ", $attr_values[$language->code]);
            }

            unset($decodedValue['keys']);
        }

        return $decodedValue;
    }
}
