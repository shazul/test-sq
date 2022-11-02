<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(NatureTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(UserGroupTableSeeder::class);
        $this->call(CompanyCatalogueTableSeeder::class);

        //this seeder is real data might have to refresh database.
        $this->call(AttributeTableSeeder::class);

//        $this->call(TechnicalProductTableSeeder::class);
//        $this->call(CatalogProductTableSeeder::class);
//        $this->call(ChildProductTableSeeder::class);
//        $this->call(ParentProductTableSeeder::class);

        // $this->call(DetailTableSeeder::class);
        // $this->call(SpecificationTableSeeder::class);
        // $this->call(TechnicalBulletinSeeder::class);

        //must run last to link medias to all data
        $this->call(MediaTableSeeder::class);
    }
}
