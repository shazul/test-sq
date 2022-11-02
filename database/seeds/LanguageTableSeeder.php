<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Pimeo\Models\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            'fr' => 'Français',
            'en' => 'English',
        ];

        foreach ($languages as $code => $name) {
            factory(Language::class)->create(compact('code', 'name'));
        }
    }
}
