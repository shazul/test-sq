<?php

namespace Pimeo\Http\Controllers\Api;

use Pimeo\Models\Specification;

class SpecificationsController extends ApiController
{
    /**
     * Show all specifications with their attributes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $specifications = Specification::with('linkAttributes.attribute', 'linkAttributes')->paginate();

        return $this->response($specifications);
    }

    /**
     * Show a specific specification with its attributes.
     *
     * @param  Specification $specification
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Specification $specification)
    {
        $specification->load('linkAttributes.attribute', 'linkAttributes');

        return $this->response($specification, ['specifications' => route('api.specifications')]);
    }
}
