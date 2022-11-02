<?php

namespace Tests\Unit;

use Pimeo\Http\Controllers\Pim\CompanyController;
use Pimeo\Http\Requests\Pim\Company\CreateRequest;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\CompanyModel;
use Pimeo\Models\ParentProduct;
use Pimeo\Repositories\CompanyRepository;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use DatabaseSetup;

    /** @var  CompanyController */
    protected $controller;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
        $this->controller = app(CompanyController::class);
    }

    public function test_an_admin_cant_list_companies()
    {
        $this->actingAs($this->admin);

        $this->get('/company')->seeStatusCode(403);
    }

    public function test_a_superadmin_can_list_companies()
    {
        $this->get('/company')->seeStatusCode(200);
    }

    public function test_create_company_default_language_english()
    {
        $data = [
            'name'      => 'test name',
            'languages' => [2],
            'models'    => [ParentProduct::class, ChildProduct::class],
        ];

        $request = new CreateRequest($data);
        $this->controller->store($request);

        $last_company_id = Company::all()->last()->id;

        $this->seeInDatabase('companies', ['id' => $last_company_id, 'name' => 'test name', 'default_language_id' => 2]);
        $this->seeInDatabase('company_model', ['company_id' => $last_company_id, 'model' => ParentProduct::class]);
        $this->seeInDatabase('company_model', ['company_id' => $last_company_id, 'model' => ChildProduct::class]);

        return $last_company_id;
    }

    public function test_create_company_created_by()
    {
        $company = $this->createValidCompany();

        $this->seeInDatabase('companies', [
            'id'         => $company->id,
            'name'       => 'test name',
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);
    }

    public function test_delete_a_company_and_related_model()
    {
        $company_id = $this->test_create_company_default_language_english();
        $companyRepository = new CompanyRepository();

        $companyRepository->delete($company_id);

        $companyModels = CompanyModel::whereCompanyId($company_id)->get();
        $this->dontSeeInDatabase('companies', ['id' => $company_id ]);
        self::assertEmpty($companyModels);
    }

    public function test_edit_company_right_fields()
    {
        $company = $this->createValidCompany();

        $this->visit("company/{$company->id}/edit");

        $this->seeInField('name', $company->name);
        $this->seeIsSelected('languages[]', $company->languages[0]->id);
        $this->seeIsSelected('languages[]', $company->languages[1]->id);
        $this->seeIsSelected('default_language_id', 2);
    }

    public function test_edit_company_updated_by()
    {
        $this->actingAs($this->editor);
        $company = $this->createValidCompany();

        $this->actingAs($this->superAdmin);
        $repo = app(CompanyRepository::class);
        $repo->update($company->id, ['users' => []]);

        $this->seeInDatabase('companies', [
            'id'         => $company->id,
            'created_by' => $this->editor->id,
            'updated_by' => $this->superAdmin->id,
        ]);
    }
}
