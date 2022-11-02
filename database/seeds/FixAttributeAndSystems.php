<?php

use Illuminate\Database\Seeder;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeLabel;
use Pimeo\Models\AttributeValue;
use Pimeo\Models\Company;
use Pimeo\Models\System;

class FixAttributeAndSystems extends Seeder
{
    /**
     * Will do a cleanup and delete all old systems
     * consolidate certain system attributes and add a new one.
     *
     * @return void
     */
    public function run()
    {

        //DELETE SYSTEMS
        $company = $this->fetchCompany();
        $systems = System::all();

        /** @var System $system */
        foreach ($systems as $system) {
            $linkAttributes = $system->linkAttributes;
            foreach ($linkAttributes as $linkAttribute) {
                $linkAttribute->delete();
            }
            $system->delete();
        }

        $name = 'system_bridging';
        if (is_null(Attribute::where('name', $name)->first())) {
            $attributeSystemBridging = [
                'system_foundation_system_bridging',
                'system_wall_system_bridging',
                'system_bridge_system_bridging',
                'system_parking_system_bridging',
                'system_roof_system_bridging',
                'system_plaza_deck_system_bridging',
                'system_balcony_system_bridging',
            ];

            $labelData = json_decode('{"fr":"Pontage","en":"Bridging"}', true);
            $values = json_decode('{"fr":["Acier","B\u00e9ton","B\u00e9ton projet\u00e9","Bloc de ma\u00e7onnerie","Bois (contreplaqu\u00e9)","Bois (planche)","Bois (trait\u00e9)","Boisage","Coffrage isol\u00e9","Colombage d\'acier","Colombage de bois","Dalle de propret\u00e9","Gypse Ext\u00e9rieur","Pierre","Sol pr\u00e9par\u00e9"],"en":["Acier","B\u00e9ton","B\u00e9ton projet\u00e9","Bloc de ma\u00e7onnerie","Bois (contreplaqu\u00e9)","Bois (planche)","Bois (trait\u00e9)","Boisage","Coffrage isol\u00e9","Colombage d\'acier","Colombage de bois","Dalle de propret\u00e9","Gypse Ext\u00e9rieur","Pierre","Sol pr\u00e9par\u00e9"]}', true);
            $typeId = 3;
            $this->cleanAttributeInArrayAndBuildANewOne(
                $name,
                $labelData,
                $attributeSystemBridging,
                $company,
                $values,
                $typeId,
                true
            );
        }

        $name = 'system_installation_method';
        if (is_null(Attribute::where('name', $name)->first())) {
            $attributeInstallationMethod = [
                'system_foundation_system_installation_method',
                'system_wall_system_installation_method',
                'system_roof_system_installation_method',
            ];

            $labelData = json_decode('{"fr":"Méthode d\'installation", "en":"Installation method"}', true);
            $values = [
                'fr' => [
                    'Adhésif ',
                    'Adhésif à froid',
                    'Autocollant',
                    'Bitume chaud SEBS/oxydé',
                    'Fixé mécaniquement',
                    'Multi II',
                    'Panneaux composites',
                    'Par plots d\'adhésif',
                    'Pleine adhérence',
                    'Semi-adhérence',
                    'Soprabase',
                    'Thermosoudé',
                    'Thermosoudé (sous dalle)',
                    'Thermosoudé (murs)',
                    'Liquide',
                    'Panneau laminé',
                    'Pulvérisé',
                    'Inversé',
                    'Ballasté',
                ],
                'en' => [
                    'Adhésif ',
                    'Adhésif à froid',
                    'Autocollant',
                    'Bitume chaud SEBS/oxydé',
                    'Fixé mécaniquement',
                    'Multi II',
                    'Panneaux composites',
                    'Par plots d\'adhésif',
                    'Pleine adhérence',
                    'Semi-adhérence',
                    'Soprabase',
                    'Thermosoudé',
                    'Thermosoudé (sous dalle)',
                    'Thermosoudé (murs)',
                    'Liquide',
                    'Panneau laminé',
                    'Pulvérisé',
                    'Inversé',
                    'Ballasté',
                ],
            ];
            $typeId = 1;
            $this->cleanAttributeInArrayAndBuildANewOne(
                $name,
                $labelData,
                $attributeInstallationMethod,
                $company,
                $values,
                $typeId
            );
        }

        $name = 'system_function';
        if (is_null(Attribute::where('name', $name)->first())) {
            //Add function to systems
            $function = [
                ['name' => 'Étanchéité', 'image' => '#'],
                ['name' => 'Isolation', 'image' => '#'],
                ['name' => 'Insonorisation', 'image' => '#'],
                ['name' => 'Végétalisation', 'image' => '#'],
                ['name' => 'Compléments', 'image' => '#'],
            ];
            $label = $this->createLabel($name, ['fr' => 'Fonction', 'en' => 'Function']);
            $this->createAttribute(
                $name,
                'system',
                ['fr' => $function, 'en' => $function],
                $company,
                4,
                $label,
                1
            );
        }
    }

    /**
     * Fetch a company.
     *
     * @return Company
     */
    protected function fetchCompany()
    {
        return Company::find(1);
    }

    /**
     * @param $modelType
     * @param $values
     *
     * @return AttributeLabel
     */
    public function createLabel($modelType, array $values)
    {
        $label = AttributeLabel::create([
            'name'   => $modelType,
            'values' => $values,
        ]);

        return $label;
    }

    /**
     * @param $name
     * @param $modelType
     * @param array $values
     * @param Company $company
     * @param $typeId
     * @param AttributeLabel $label
     * @param bool $min_require
     *
     * @return Attribute
     */
    private function createAttribute(
        $name,
        $modelType,
        array $values,
        Company $company,
        $typeId,
        AttributeLabel $label,
        $min_require = false
    ) {
        $attributes = [
            'name'                => $name,
            'model_type'          => $modelType,
            'has_value'           => 1,
            'is_parent_attribute' => 0,
            'options'             => json_decode('{}'),
            'is_min_requirement'  => $min_require,
            'company_id'          => $company->id,
            'attribute_type_id'   => $typeId,
            'attribute_label_id'  => $label->id,
            'last_edited_by'      => 1,
        ];

        $attribute = Attribute::create($attributes);
        factory(AttributeValue::class)->create(
            [
                'attribute_id' => $attribute->id,
                'values'       => $values,
            ]
        );

        return $attribute;
    }

    /**
     * @param $name
     * @param $label_values
     * @param $attributesArray
     * @param $company
     * @param $attributeValues
     * @param $typeId
     * @param bool $isMinRequirement
     *
     * @return Attribute
     * @throws Exception
     */
    private function cleanAttributeInArrayAndBuildANewOne(
        $name,
        $label_values,
        $attributesArray,
        $company,
        $attributeValues,
        $typeId,
        $isMinRequirement = false
    ) {
        foreach ($attributesArray as $attrName) {
            /** @var Attribute $attribute */
            $attribute = Attribute::where('name', '=', $attrName)->first();
            if (!is_null($attribute)) {
                $attribute->delete();
            }
        }

        $label = $this->createLabel($name, $label_values);
        return $this->createAttribute(
            $name,
            'system',
            $attributeValues,
            $company,
            $typeId,
            $label,
            $isMinRequirement
        );
    }
}
