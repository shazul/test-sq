<?php

use Illuminate\Database\Seeder;
use Pimeo\Models\CompanyBlog;

class CompanyBlogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CompanyBlog::class)->create([
            'blog_id'     => 4200466208,
            'language_id' => 1,
            'company_id'  => 1,
        ]);

        factory(CompanyBlog::class)->create([
            'blog_id'     => 4200425933,
            'language_id' => 2,
            'company_id'  => 1,
        ]);
    }
}
