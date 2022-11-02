<?php

namespace Pimeo\Transformers;

use League\Fractal\TransformerAbstract;
use Pimeo\Models\Company;

class CompaniesTransformer extends TransformerAbstract
{
    public function transform(Company $company)
    {
        return [
            'id'       => $company->id,
            'name'     => $company->name,
            'language' => $company->default_language_id,
        ];
    }
}
