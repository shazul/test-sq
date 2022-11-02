<?php

use Illuminate\Database\Seeder;
use Pimeo\Models\Nature;

class NatureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $natures = ['Autre', 'Feuille', 'Liquide', 'Panneau'];

        foreach ($natures as $name) {
            $code = strtoupper(substr($name, 0, 1));

            factory(Nature::class)->create(compact('code', 'name'));
        }
    }
}
