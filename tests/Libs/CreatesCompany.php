<?php

namespace Tests\Libs;

use Illuminate\Foundation\Auth\User;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Specification;
use Pimeo\Repositories\CompanyRepository;

trait CreatesCompany
{
    public function createValidCompany($data = [])
    {
        $defaultData = [
            'name'                => 'test name',
            'languages'           => [1, 2],
            'default_language_id' => 2,
            'models'              => [ChildProduct::class, Specification::class],
            'users'               => User::all()->pluck('id')->toArray(),
        ];

        $data = array_merge($defaultData, $data);

        $company_repository = new CompanyRepository();

        $company = $company_repository->create($data);

        return $company;
    }
}
