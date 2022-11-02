<?php

namespace Pimeo\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class UnitFieldComposer
{
    public function compose(View $view)
    {
        $units = collect([
            '˚c',
            'mm',
            'm',
            'm2',
            'kg',
            'L',
            'kg/m2',
        ]);

        $has_conversion = collect([
            'mm',
            'm',
            'm2',
            'kg',
            'L',
            'kg/m2',
        ]);

        $view->with(compact('units', 'has_conversion'));
    }
}
