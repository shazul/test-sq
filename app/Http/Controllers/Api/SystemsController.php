<?php

namespace Pimeo\Http\Controllers\Api;

use Pimeo\Models\System;

class SystemsController extends ApiController
{
    /**
     * Show all systems with their attributes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $systems = System::with('linkAttributes.attribute', 'linkAttributes')->paginate();

        return $this->response($systems);
    }

    /**
     * Show a specific system with its attributes.
     *
     * @param  System $system
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(System $system)
    {
        $system->load('linkAttributes.attribute', 'linkAttributes');

        return $this->response($system, ['systems' => route('api.systems')]);
    }
}
