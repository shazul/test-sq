<?php

namespace Pimeo\Services\Fractal;

trait Fractable
{
    public function newCollection(array $models = [])
    {
        $fractalClass = $this->getFractalCollectionClass();

        return new $fractalClass($models);
    }

    protected function getFractalCollectionClass()
    {
        if (property_exists($this, 'fractal')) {
            return $this->fractal;
        }

        return FractalCollection::class;
    }
}
