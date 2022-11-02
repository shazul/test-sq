<?php

namespace Pimeo\Transformers\AttributeTypes;

use Pimeo\Models\LinkAttribute;

abstract class AbstractTypeTransformer
{
    /**
     * The attribute's type options.
     *
     * @var array
     */
    protected $options;

    /**
     * Create a new type transformer instance.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Transform the given values.
     *
     * @param LinkAttribute $linkAttribute
     *
     * @return mixed
     */
    abstract public function transform(LinkAttribute $linkAttribute);
}
