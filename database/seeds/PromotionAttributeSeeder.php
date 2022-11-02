<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeLabel;
use Pimeo\Models\AttributeType;
use Pimeo\Models\Company;
use Pimeo\Models\Nature;

class PromotionAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = $this->getCompanies();
        $natures = $this->getNatures();
        $type = $this->createType();
        $label = $this->createLabel();

        $companies->each(function (Company $company) use ($natures, $type, $label) {
            $attribute = $this->createAttribute($company->id, $type->id, $label->id);
            $attribute->natures()->attach($natures);
        });
    }

    /**
     * @param  int $companyId
     * @param  int $typeId
     * @param  int $labelId
     *
     * @return Attribute
     */
    private function createAttribute($companyId, $typeId, $labelId)
    {
        return Attribute::updateOrCreate([
            'company_id'         => $companyId,
            'attribute_type_id'  => $typeId,
            'attribute_label_id' => $labelId,
            'name'               => 'promotion',
        ], [
            'model_type'          => 'parent_product',
            'is_parent_attribute' => true,
            'is_min_requirement'  => false,
            'should_index'        => true,
            'has_value'           => false,
            'options'             => [],
        ]);
    }

    /**
     * @return AttributeLabel
     */
    private function createLabel()
    {
        return AttributeLabel::updateOrCreate([
            'name' => 'promotion',
        ], [
            'values' => [
                'fr' => 'Promotion',
                'en' => 'Promotion',
            ],
        ]);
    }

    /**
     * @return AttributeType
     */
    private function createType()
    {
        return AttributeType::updateOrCreate([
            'code' => 'promotion',
        ], [
            'name'   => 'Promotion',
            'public' => false,
            'specs'  => [
                'type'     => 'promotion',
                'multiple' => 0,
                'access'   => 'private',
            ],
        ]);
    }

    /**
     * @return Collection|Company[]
     */
    private function getCompanies()
    {
        return Company::all();
    }

    /**
     * @return Collection|Nature[]
     */
    private function getNatures()
    {
        return Nature::all();
    }
}
