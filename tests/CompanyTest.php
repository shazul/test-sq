<?php

namespace Tests;

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\User;
use Tests\Libs\DatabaseSetup;

class CompanyTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();
    }

    public function test_an_admin_can_create_a_company()
    {
        $this->createACompany();
        $this->see('Company successfuly created');
    }

    public function test_an_admin_can_edit_a_company()
    {
        $company = $this->createValidCompany();

        $this->visit('/company/' . $company->id . '/edit')
            ->see('Edit a company')
            ->type('Soprema - Suisse', 'name')
            ->press('Save');

        $this->see('Company successfuly updated');
    }

    public function test_a_user_can_change_company()
    {
        $this->createACompany();
        $companyId = Company::all()->last()->id;

        $this->visit('/user/change/company/' . $companyId);

        $this->assertEquals(\Auth::user()->active_company_id, $companyId);
        $this->assertEquals(\Auth::user()->activeCompany->id, $companyId);
    }

    public function test_a_company_doesnt_have_access_to_a_restricted_model()
    {
        $company = $this->createValidCompany();

        $this->visit('/user/change/company/' . $company->id);

        $this->visit('/parent-product/listing')
            ->see('Homepage');
    }

    private function createACompany()
    {
        $this->visit('/company/create')
            ->type('Soprema (Suisse)', 'name')
            ->select([ParentProduct::class], 'models[]')
            ->press('Save');
    }
}
