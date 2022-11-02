<?php

namespace Pimeo\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Pimeo\Models\AttributableModelStatus;

trait ModelListing
{
    protected function listAll($key, Request $request, $route, $repositoryClass, $status, $repositoryParameters = [])
    {
        $repository = app($repositoryClass);

        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 20;

        $status_list = $this->getStatusList();

        $sorting = $request->get('sort');

        $parameters = [];
        if (is_array($route)) {
            $parameters = $route;
            $route = array_shift($parameters);
        }

        $products = [];

        foreach ($status_list as $name => $status_content) {
            $isActiveTab = ($status == $name);

            $repoParameters = array_merge(
                $repositoryParameters,
                [$name, $sorting, current_language_code(), $request->input('search')]
            );

            // @todo Fix this bottleneck !
            $products[$name] = call_user_func_array([$repository, 'allForListing'], $repoParameters);

            $status_list[$name] = [
                'name'        => $name,
                'nb_products' => count($products[$name]),
                'link'        => route($route, array_merge($parameters, [$name])),
                'active'      => $isActiveTab,
            ];
        }

        $recherche = $request->input('search');
        if ($recherche != null) {
            // Filtre les produits de tous les types pour avoir le bon count
            foreach ($status_list as $status_name => $status_content) {
                $products[$status_name] = $this->filterProducts($products[$status_name], $recherche);
                $status_list[$status_name]['nb_products'] = count($products[$status_name]);
            }
        }

        $products = $products[$status];

        $product_count = count($products);
        $products = array_slice($products, ($currentPage - 1) * $perPage, $perPage, true);

        $paginator = new LengthAwarePaginator(
            $products,
            $product_count,
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $paginator->appends([
           'sort'   => $request->query('sort'),
           'search' => $request->query('search'),
        ]);

        list($sort_column, $sort_order) = explode(':', $sorting ?: ':');

        return [
            $key          => $paginator,
            'status_list' => $status_list,
            'sort_column' => $sort_column,
            'sort_order'  => $sort_order,
        ];
    }

    protected function getStatusList()
    {
        return [
            AttributableModelStatus::COMPLETE_STATUS   => [],
            AttributableModelStatus::INCOMPLETE_STATUS => [],
        ];
    }
}
