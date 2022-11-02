<?php

namespace Pimeo\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class RememberListingArguments
{
    private $argumentsToSave;
    private $argumentsToSaveForTab;
    private $productType;
    private $tabRouteArguments;
    private $request;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;
        $this->argumentsToSave = ['search', 'sort', 'page'];
        $this->argumentsToSaveForTab = ['page'];

        // First part of route, so parent-product.index => parent-product
        $this->productType = explode('.', $request->route()->getName())[0];

        $this->tabRouteArguments['status'] = Route::input('status');

        $this->saveArgumentsInSession();
        $this->putSessionArgumentsInRequest();
        $newTab = $this->setDefaultTab();

        if ($newTab !== false) {
            return redirect()->route($this->request->route()->getName(), $newTab);
        }

        return $next($request);
    }

    private function saveArgumentsInSession()
    {
        foreach ($this->argumentsToSave as $argument) {
            $value = $this->request->get($argument);
            if ($value !== null) {
                $this->request->session()->push('listingParams', [
                    'argument' => $argument,
                    'value'    => $value,
                    'type'     => $this->productType,
                    'tab'      => $this->tabRouteArguments,
                ]);
            }
        }
    }

    private function putSessionArgumentsInRequest()
    {
        $listingParams = $this->request->session()->get('listingParams');

        if ($listingParams == null) {
            return;
        }

        foreach ($listingParams as $listingParam) {
            // Different route so clear the saved params
            if ($listingParam['type'] != $this->productType) {
                $this->request->session()->forget('listingParams');
                return;
            }

            // Some arguments are saved only for a tab
            if (in_array($listingParam['argument'], $this->argumentsToSaveForTab)) {
                if ($listingParam['tab'] != $this->tabRouteArguments) {
                    continue;
                }
            }

            // Finalement, push l'argument savé dans la request
            if (in_array($listingParam['argument'], $this->argumentsToSave)) {
                $this->request->merge([$listingParam['argument'] => $listingParam['value']]);
            }
        }
    }

    /**
     * Save le tab actuel du listing. Si vide, redirige au tab qui était sélectionné précédemment
     * @return false : Pas de redirection
     * @return string Tab à lequel rediriger
     */
    private function setDefaultTab()
    {
        $routeType = explode('.', $this->request->route()->getName());

        // Valide qu'on est dans un listing
        if (!isset($routeType[1])) {
            return false;
        }
        $routeType = $routeType[1];
        if ($routeType != 'index') {
            return false;
        }

        if ($this->tabRouteArguments['status'] !== null) {
            $this->request->session()->put('currentTab', [
                'type'     => $this->productType,
                'value'    => $this->tabRouteArguments,
            ]);
        }

        if ($this->tabRouteArguments['status'] === null && $this->request->session()->has('currentTab')) {
            $savedTab = $this->request->session()->get('currentTab');

            // Switch de type de produit, donc forget le current tab
            if ($savedTab['type'] != $this->productType) {
                $this->request->session()->forget('currentTab');
                return false;
            }
            return $savedTab['value'];
        }
        return false;
    }
}
