<?php

namespace Pimeo\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Add the given route (title and link) + listing to breadcrumb
     * TODO: receive an array of routes and add them to breadcrumb
     *
     * @param string $model Model name
     * @param string $title
     * @param array|string $link
     */
    protected function breadcrumb($model, $title = null, $link = null)
    {
        breadcrumb()->add(trans('breadcrumb.' . $model . 's.listing'), $model . '.index');

        if ($title) {
            breadcrumb()->add($title, $link);
        }
    }

    protected function filterProducts($products, $recherche)
    {
        $filteredProducts = [];

        $userLang = current_language_code();

        $recherche = trim($recherche);

        foreach ($products as $key => $product) {
            foreach ($product as $v) {
                if (isset($v[$userLang])) {
                    if (stristr($v[$userLang], $recherche) !== false) {
                        $filteredProducts[$key] = $product;
                    }
                } elseif (!is_array($v)) {
                    if (stristr($v, $recherche) !== false) {
                        $filteredProducts[$key] = $product;
                    }
                } elseif (is_array($v)) { // For building components
                    if (isset($v['keys']) && !empty($v['keys'])) {
                        if (stristr(implode($v, ', '), $recherche) !== false) {
                            $filteredProducts[$key] = $product;
                        }
                    }
                }
            }
        };

        return $filteredProducts;
    }
}
