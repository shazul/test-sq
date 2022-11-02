<?php

namespace Tests\Unit;

use Pimeo\Events\Pim\CompanyWasDeleted;
use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Models\Company;
use Pimeo\Models\User;
use Pimeo\Repositories\CompanyRepository;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class CompanyRepositoryTest extends TestCase
{
    use DatabaseSetup;

    /** @var CompanyRepository */
    protected $class;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();

        $this->class = app(CompanyRepository::class);
    }

    public function test_create_company_gives_access_to_superadmin()
    {
        $this->createValidCompany();

        $last_company_id = Company::all()->last()->id;
        $superadmin_id = User::first()->id;

        $this->seeInDatabase('company_user', ['company_id' => $last_company_id, 'user_id' => $superadmin_id]);
    }

    public function test_create_company_associate_languages()
    {
        $this->createValidCompany();

        $this->seeInDatabase('companies', ['name' => 'test name', 'default_language_id' => 2]);

        $last_company_id = Company::all()->last()->id;

        $this->seeInDatabase('company_language', ['company_id' => $last_company_id, 'language_id' => 1]);
        $this->seeInDatabase('company_language', ['company_id' => $last_company_id, 'language_id' => 2]);
    }

    public function test_update_company()
    {
        $company = $this->createValidCompany();
        $superadmin_id = User::first()->id;

        $newData = [
            'name'                => 'test name 2',
            'default_language_id' => 1,
            'users'               => 'novalue',
        ];

        $newCompany = $this->class->update($company->id, $newData);

        $this->seeInDatabase('companies', ['name' => 'test name 2', 'default_language_id' => 1]);
        $this->seeInDatabase('company_user', ['company_id' => $newCompany->id, 'user_id' => $superadmin_id]);
        $this->seeInDatabase('company_language', ['company_id' => $newCompany->id, 'language_id' => 2]);
    }

    public function test_delete_company()
    {
        $superadmin_id = User::first()->id;
        $company = $this->createValidCompany();

        $this->expectsEvents(CompanyWasDeleted::class);

        $this->class->delete($company->id);

        $this->dontSeeInDatabase('companies', ['name' => 'test name', 'default_language_id' => 2]);
        $this->dontSeeInDatabase('company_user', ['company_id' => $company->id, 'user_id' => $superadmin_id]);
        $this->dontSeeInDatabase('company_language', ['company_id' => $company->id, 'language_id' => 2]);
    }
}
