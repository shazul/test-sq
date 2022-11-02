<?php

use Illuminate\Database\Seeder;
use Pimeo\Models\BuildingComponent;
use Pimeo\Models\Company;
use Pimeo\Models\CompanyModel;
use Pimeo\Models\Language;
use Pimeo\Repositories\CompanyModelRepository;

class CompanyTableSeeder extends Seeder
{
    const COMPANY_NAME = 'SOPREMA (Canada)';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertCompany();
    }

    private function insertCompany()
    {
        $main_lang = Language::where('code', 'en')->first();

        /** @var Company $company */
        $company = factory(Company::class)->create([
            'name' => self::COMPANY_NAME,
            'default_language_id' => $main_lang->id
        ]);

        Language::all()->each(function ($lang) use ($company) {
            $company->languages()->attach($lang);
        });

        BuildingComponent::query()->update(['company_id' => 1]);

        $models = array_keys(CompanyModelRepository::getAllAvailableModelForDisplay());

        foreach ($models as $model) {
            CompanyModel::create(['company_id' => $company->id, 'model' => $model]);
        }
    }
}
