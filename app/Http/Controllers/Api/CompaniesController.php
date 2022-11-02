<?php

namespace Pimeo\Http\Controllers\Api;

use Pimeo\Models\Company;
use Pimeo\Models\Media;

class CompaniesController extends ApiController
{
    /**
     * Show all companies.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $companies = Company::paginate();

        return $this->response($companies);
    }

    /**
     * Show a company with its languages.
     *
     * @param  Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Company $company)
    {
        $company->load('languages');

        return $this->response($company, ['companies' => route('api.companies')]);
    }

    /**
     * Show languages associated with the specified company.
     *
     * @param  Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function languages(Company $company)
    {
        $languages = $company->languages()->get();

        return $this->response($languages, ['company' => route('api.company', $company)]);
    }

    /**
     * Show products with their attributes for a specified company.
     *
     * @param  Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function products(Company $company)
    {
        $paginator = $company->parentProducts()->with('linkAttributes', 'linkAttributes.attribute')->paginate();

        return $this->response($paginator, ['company' => route('api.company', $company)]);
    }

    /**
     * Show systems with their attributes for a specified company.
     *
     * @param  Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function systems(Company $company)
    {
        $systems = $company->systems()->with('linkAttributes', 'linkAttributes.attribute')->paginate();

        return $this->response($systems, ['company' => route('api.company', $company)]);
    }

    /**
     * Show details with their attributes for a specified company.
     *
     * @param  Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function details(Company $company)
    {
        $details = $company->details()->with('linkAttributes', 'linkAttributes.attribute')->paginate();

        return $this->response($details, ['company' => route('api.company', $company)]);
    }

    /**
     * Show specifications with their attributes for a specified company.
     *
     * @param  Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function specifications(Company $company)
    {
        $specifications = $company->specifications()
                                  ->with('linkAttributes', 'linkAttributes.attribute')
                                  ->paginate();

        return $this->response($specifications, ['company' => route('api.company', $company)]);
    }

    /**
     * Show technical bulletins with their attributes for the specified company.
     *
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function technicalBulletins(Company $company)
    {
        $technicalBulletins = $company->technicalBulletins()
                                      ->with('linkAttributes', 'linkAttributes.attribute')
                                      ->paginate();

        return $this->response($technicalBulletins, ['company' => route('api.company', $company)]);
    }

    /**
     * Show medias for a specified company.
     *
     * @param  Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function medias(Company $company)
    {
        $medias = $company->medias()->get();

        return $this->response($medias, ['company' => route('api.company', $company)]);
    }

    /**
     * Show products for a specified company and media.
     *
     * @param  Company $company
     * @param  Media   $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaProducts(Company $company, Media $media)
    {
        $relations = [
            'mediaLinks',
            'linkAttributes',
            'linkAttributes.attribute',
        ];

        $products = $company->parentProducts()->with($relations)
            ->whereHas('mediaLinks', function ($query) use ($media) {
                $query->where('link_medias.media_id', $media->id);
            })->paginate();

        return $this->response($products, ['medias' => route('api.company.medias', $company)]);
    }

    /**
     * Show system for a specified company and media.
     *
     * @param  Company $company
     * @param  Media   $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaSystems(Company $company, Media $media)
    {

        $relations = [
            'mediaLinks',
            'linkAttributes',
            'linkAttributes.attribute',
            'layerGroups',
            'layerGroups.layers'
        ];

        $systems = $company->systems()->with($relations)->whereHas('mediaLinks', function ($query) use ($media) {
            $query->where('link_medias.media_id', $media->id);
        })->paginate();

        return $this->response($systems, ['medias' => route('api.company.medias', $company)]);
    }
}
