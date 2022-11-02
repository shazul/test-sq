<?php

namespace Pimeo\Forms\Fields;

class UnitCelsius extends Unit
{
    public function getOperation()
    {
        return '* 1.8 +';
    }
}
