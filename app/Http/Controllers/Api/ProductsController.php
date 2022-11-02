<?php

namespace Pimeo\Http\Controllers\Api;

use Illuminate\Http\Request;
use Pimeo\Models\ParentProduct;

class ProductsController extends ApiController
{
    /**
     * Show all products with their attributes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $products = ParentProduct::with('linkAttributes.attribute', 'linkAttributes')->paginate();

        return $this->response($products);
    }

    /**
     * Show a product with its attributes and children if specified.
     *
     * @param  Request       $request
     * @param  ParentProduct $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, ParentProduct $product)
    {
        $product = $product->load('linkAttributes.attribute', 'linkAttributes');

        if ($request->has('with_children')) {
            $product->load(
                'childProducts',
                'childProducts.linkAttributes',
                'childProducts.linkAttributes.attribute'
            );
        }

        return $this->response($product, ['products' => route('api.products')]);
    }
}
