<?php

namespace Pimeo\Transformers;

use League\Fractal\TransformerAbstract;
use Pimeo\Models\LinkAttribute;
use Pimeo\Transformers\AttributeTypes\AbstractTypeTransformer;

abstract class Transformer extends TransformerAbstract
{
    protected static $languages = [];

    protected static $units = [];

    public static function setLanguages(array $languages)
    {
        self::$languages = $languages;
    }

    public static function setUnits(array $units)
    {
        self::$units = $units;
    }

    protected function flattenAttribute($linkable)
    {
        $attributes = [];

        foreach ($linkable->linkAttributes as $linkAttribute) {
            $attributes[$linkAttribute->attribute->name] = $this->flattenValues($linkAttribute);
        }

        return $attributes;
    }

    protected function flattenValues(LinkAttribute $linkAttribute)
    {
        $type = $linkAttribute->attribute->type;

        $className = $this->getClassName($type->specs['type']);

        /** @var AbstractTypeTransformer $transformer */
        $transformer = app($className, [$type->specs]);

        $values = $transformer->transform($linkAttribute);

        if (is_array($values)) {
            $values = $this->getValue($values);
        }

        return $values;
    }

    protected function getValue($values)
    {
        $keeps = array_merge(self::$languages, self::$units);

        if (count($keeps)) {
            return array_filter($values, function ($key) use ($keeps) {
                return in_array($key, $keeps);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $values;
    }

    protected function getClassName($type)
    {
        return __NAMESPACE__ . '\AttributeTypes\\' . ucfirst($type).'Transformer';
    }
}
