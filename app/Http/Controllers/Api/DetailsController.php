<?php

namespace Pimeo\Http\Controllers\Api;

use Pimeo\Models\Detail;

class DetailsController extends ApiController
{
    /**
     * Show all details with their attributes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $details = Detail::with('linkAttributes.attribute', 'linkAttributes')->paginate();

        return $this->response($details);
    }

    /**
     * Show a detail with its attributes.
     *
     * @param  Detail $detail
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Detail $detail)
    {
        $detail->load('linkAttributes.attribute', 'linkAttributes');

        return $this->response($detail, ['details' => route('api.details')]);
    }
}
