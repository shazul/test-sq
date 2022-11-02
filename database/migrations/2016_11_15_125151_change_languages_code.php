<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Pimeo\Models\Language;

class ChangeLanguagesCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $languages = Language::all();

        if ($languages->count()) {
            /** @var Language $language */
            foreach ($languages as $language) {
                $language->code = substr($language->code, 0, 2);
                $language->save();
            }

            $fr = Language::where('code', 'fr')->first();
            if ($fr) {
                $fr->name = 'Français';
                $fr->save();
            }

            $en = Language::where('code', 'en')->first();
            if ($en) {
                $en->name = 'English';
                $en->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $languages = Language::all();

        if ($languages->count()) {
            /** @var Language $language */
            foreach ($languages as $language) {
                $language->code = $language->code . '_CA';
                $language->save();
            }

            $fr = Language::where('code', 'fr_CA')->first();
            if ($fr) {
                $fr->name = 'Français (Canada)';
                $fr->save();
            }

            $en = Language::where('code', 'en_CA')->first();
            if ($en) {
                $en->name = 'English (Canada)';
                $en->save();
            }
        }
    }
}
