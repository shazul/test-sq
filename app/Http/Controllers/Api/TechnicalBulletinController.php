<?php

namespace Pimeo\Http\Controllers\Api;

use Pimeo\Models\TechnicalBulletin;

class TechnicalBulletinController extends ApiController
{
    /**
     * Show all technical bulletins with their attributes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $technicalBulletins = TechnicalBulletin::with('linkAttributes.attribute', 'linkAttributes')->paginate();

        return $this->response($technicalBulletins);
    }

    /**
     * Show a technical bulletin with its attributes.
     *
     * @param  TechnicalBulletin $technicalBulletin
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(TechnicalBulletin $technicalBulletin)
    {
        $technicalBulletin->load('linkAttributes.attribute', 'linkAttributes');

        return $this->response($technicalBulletin, ['technical-bulletins' => route('api.technical-bulletins')]);
    }
}
