<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\CompanyModel;
use Pimeo\Models\Detail;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Pimeo\Models\TechnicalBulletin;
use Pimeo\Repositories\CompanyModelRepository;

class CompanyModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_model', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->string('model');

            $table->foreign('company_id')->references('id')->on('companies');
        });

        CompanyModelRepository::createAllForExistingCompanies();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company_model');
    }
}
