<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Pimeo\Models\Company;
use Pimeo\Models\CompanyCatalog;

class CompanyCatalogueTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::where('name', CompanyTableSeeder::COMPANY_NAME)->first();

        factory(CompanyCatalog::class)->create([
            'company_id' => $company->id
        ]);
    }
}
