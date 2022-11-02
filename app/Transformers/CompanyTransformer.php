<?php

namespace Pimeo\Transformers;

use League\Fractal\TransformerAbstract;
use Pimeo\Models\Company;

class CompanyTransformer extends TransformerAbstract
{
    public function transform(Company $company)
    {
        return [
            'id'       => $company->id,
            'name'     => $company->name,
            'language' => [
                'name' => $company->defaultLanguage->name,
                'code' => $company->defaultLanguage->code,
            ],
        ];
    }
}
