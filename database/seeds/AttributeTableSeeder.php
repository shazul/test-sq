<?php

use Illuminate\Database\Seeder;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeLabel;
use Pimeo\Models\AttributeType;
use Pimeo\Models\AttributeValue;
use Pimeo\Models\Company;
use Pimeo\Models\Nature;
use Pimeo\Models\User;

class AttributeTableSeeder extends Seeder
{
    const ROOF_SYSTEM = 'roof_system';
    const BALCONY_SYSTEM = 'balcony_system';
    const BRIDGE_SYSTEM = 'bridge_system';
    const PARKING_SYSTEM = 'parking_system';
    const FOUNDATION_SYSTEM = 'foundation_system';
    const WALL_SYSTEM = 'wall_system';
    const PLAZA_DECK_SYSTEM = 'plaza_deck_system';
    const FOUNTAINS_SYSTEM = 'fountains_system';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = $this->createAttributeTypes();
        $company = Company::where('name', CompanyTableSeeder::COMPANY_NAME)->first();
        $user = User::where('email', UserGroupTableSeeder::SUPER_ADMIN_LOGIN_EMAIL)->first();

        $parent_attributes = $this->getBaseParentAttribute();
        $this->createAttributesFromArray($user, $company, $types, $parent_attributes);

        $child_attributes = $this->getChildAttributes();
        $this->createAttributesFromArray($user, $company, $types, $child_attributes);

        $spec_attributes = $this->getSpecificationAttributes();
        $this->createAttributesFromArray($user, $company, $types, $spec_attributes);

        $detail_attributes = $this->getDetailAttributes();
        $this->createAttributesFromArray($user, $company, $types, $detail_attributes);

        $technical_bulletin_attributes = $this->getTechnicalBulletinAttributes();
        $this->createAttributesFromArray($user, $company, $types, $technical_bulletin_attributes);

        $system_attributes = $this->getSystemAttributes();
        $this->createAttributesFromArray($user, $company, $types, $system_attributes);
    }

    /**
     * Creates attributes from vales in an array
     *
     * @param $user
     * @param $company
     * @param $types
     * @param $attributes
     */
    private function createAttributesFromArray($user, $company, $types, $attributes)
    {
        foreach ($attributes as $attribute) {
            $type = $types[$attribute['type']];

            $natures = null;
            if (isset($attribute['nature'])) {
                $natures = $this->getNatureIds($attribute['nature']);
            }
            $label = $this->createLabel($attribute['label']['name'], json_decode($attribute['label']['values']));

            $is_parent = false;
            if ($attribute['model_type'] == 'parent_product') {
                $is_parent = true;
            }

            $this->createAttribute(
                $attribute['name'],
                $attribute['model_type'],
                $attribute['has_value'],
                $attribute['values'],
                $is_parent,
                $attribute['is_minimum_requirement'],
                $attribute['options'],
                $type,
                $label,
                $company,
                $user,
                $natures
            );
        }
    }

    /**
     * @param array $natureCodes
     * @return array
     */
    protected function getNatureIds(array $natureCodes)
    {
        $natureCodes = array_filter($natureCodes, function ($value) {
            return !!$value;
        });

        /** @var Nature[]|\Illuminate\Database\Eloquent\Collection $natures */
        $natures = Nature::whereIn('code', $natureCodes)->get(['id']);

        return $natures->pluck('id')->toArray();
    }

    /**
     * Create a label with the given name and translations values.
     *
     * @param  string $name
     * @param  string $values
     * @return AttributeLabel
     */
    protected function createLabel($name, $values)
    {
        return factory(AttributeLabel::class)->create(compact('name', 'values'));
    }

    /**
     * Create an attribute with the given information.
     *
     * @param string $name
     * @param string $model_type
     * @param bool $has_value
     * @param string $values
     * @param bool $is_parent
     * @param bool $is_min_requirement
     * @param string $options
     * @param AttributeType $type
     * @param AttributeLabel $label
     * @param Company $company
     * @param User $user
     * @param array|null $natures
     */
    protected function createAttribute(
        $name,
        $model_type,
        $has_value,
        $values,
        $is_parent,
        $is_min_requirement,
        $options,
        AttributeType $type,
        AttributeLabel $label,
        Company $company,
        User $user,
        $natures
    ) {
        $attributes = [
            'name'                => $name,
            'model_type'          => $model_type,
            'has_value'           => $has_value,
            'is_parent_attribute' => $is_parent,
            'options'             => json_decode($options),
            'is_min_requirement'  => $is_min_requirement,
            'company_id'          => $company->id,
            'attribute_type_id'   => $type->id,
            'attribute_label_id'  => $label->id,
            'created_by'          => $user->id,
            'updated_by'           => $user->id,
        ];

        /** @var Attribute $attribute */
        $attribute = factory(Attribute::class)->create($attributes);

        if (!is_null($natures)) {
            $attribute->natures()->attach($natures);
        }

        if ($has_value && !is_null($values)) {
            $this->createAttributeValues($attribute, $values);
        }
    }

    /**
     * Creates an attribute value
     *
     * @param Attribute $attribute
     * @param $values
     */
    private function createAttributeValues(Attribute $attribute, $values)
    {
        factory(AttributeValue::class)->create(
            [
                'attribute_id' => $attribute->id,
                'values'       => json_decode($values),
            ]
        );
    }

    /**
     * Fait l'ajout de tous les types de base pour le PIM
     */
    private function createAttributeTypes()
    {
        $attributes_types = [
            'choice'                           => [
                'name'   => 'Liste de choix',
                'public' => true,
                'spec'   => '{"type":"choice","expended":0,"multiple":0,"access":"public"}',
            ],
            'choice_image_no_display'          => [
                'name'   => 'Liste de choix',
                'public' => false,
                'spec'   => '{"type":"choice","sub_type":"imageNoDisplay","expended":0,"multiple":0,"access":"public"}',
            ],
            'choice_multiple'                  => [
                'name'   => 'Liste de choix multiple',
                'public' => true,
                'spec'   => '{"type": "choice","expended":0,"multiple":1,"access":"public"}',
            ],
            'choice_multiple_image_no_display' => [
                'name'   => 'Liste de choix multiple d\'image non affiché',
                'public' => false,
                'spec'   => '{"type": "choice","sub_type":"imageNoDisplay","expended":0,"multiple":1,"access":"public"}',
            ],
            'image_choice_multiple'            => [
                'name'   => 'Liste de choix multiple d\'image',
                'public' => false,
                'spec'   => '{"type":"choice","sub_type":"image","expended":0,"multiple":1,"access":"private"}',
            ],
            'choice_checkbox_multiple'         => [
                'name'   => 'Boites de choix',
                'public' => true,
                'spec'   => '{"type":"choice","expended":1,"multiple":1,"access":"public"}',
            ],
            'file'                             => [
                'name'   => 'Fichier',
                'public' => true,
                'spec'   => '{"type":"file","multiple":0,"access":"public"}',
            ],
            'files'                            => [
                'name'   => 'Fichiers',
                'public' => true,
                'spec'   => '{"type": "file","multiple":1,"access":"private"}',
            ],
            'image'                            => [
                'name'   => 'Image',
                'public' => true,
                'spec'   => '{"type":"file","multiple":0,"access":"public"}',
            ],
            'text'                             => [
                'name'   => 'Texte',
                'public' => true,
                'spec'   => '{"type":"text","multiple":0,"access":"public"}',
            ],
            'text_hyperlink'                   => [
                'name'   => 'Lien',
                'public' => true,
                'spec'   => '{"type":"text","sub_type":"url","multiple":0,"access":"public"}',
            ],
            'text_multiple'                    => [
                'name'   => 'Textes',
                'public' => false,
                'spec'   => '{"type":"text","multiple": 1,"access":"public"}',
            ],
            'textarea'                         => [
                'name'   => 'Long Text',
                'public' => true,
                'spec'   => '{"type":"textarea","multiple":0,"access":"public"}',
            ],
            'number'                           => [
                'name'   => 'Nombre',
                'public' => true,
                'spec'   => '{"type":"number","multiple":0,"access":"public"}',
            ],
            'text_link_multiple'               => [
                'name'   => 'Multiple text link',
                'public' => false,
                'spec'   => '{"type":"text","sub_type":"link","multiple":0,"separator":",","access":"private"}',
            ],
            'unit_celsius'                     => [
                'name'   => 'Unit Celsius',
                'public' => true,
                'spec'   => '{"type":"unit","multiple":0,"access":"public","conversion":1,"metric":"celsious","metric_measurement":1,"metric_annotation":"⁰C","imperial":"farenheit","imperial_measurement":32,"imperial_annotation":"⁰F"}',
            ],
            'unit_mm_to_mil'                   => [
                'name'   => 'Unit millimeter to mils',
                'public' => true,
                'spec'   => '{"type":"unit","multiple":0,"access":"public","conversion":1,"metric":"millimeter","metric_measurement":1,"metric_annotation":"mm","imperial":"mils","imperial_measurement":39.3701,"imperial_annotation":"mil"}',
            ],
            'unit_m_to_ft'                     => [
                'name'   => 'Unit meter to foot',
                'public' => true,
                'spec'   => '{"type":"unit","multiple":0,"access":"public","conversion":1,"metric":"meter","metric_measurement":1,"metric_annotation":"m","imperial":"foot","imperial_measurement":3.28084,"imperial_annotation":"ft"}',
            ],
            'unit_mm_to_in'                    => [
                'name'   => 'Unit millimeter to inch',
                'public' => true,
                'spec'   => '{"type":"unit","multiple":0,"access":"public","conversion":1,"metric":"millimeter","metric_measurement":1,"metric_annotation":"mm","imperial":"inch","imperial_measurement":0.0393701,"imperial_annotation":"in"}',
            ],
            'unit_m2_to_ft2'                   => [
                'name'   => 'Unit square meter to square foot',
                'public' => true,
                'spec'   => '{"type":"unit","multiple":0,"access":"public","conversion":1,"metric":"square meter","metric_measurement":1,"metric_annotation":"m2","imperial":"square foot","imperial_measurement":10.7639,"imperial_annotation":"ft2"}',
            ],
            'unit_kg_to_lbs'                   => [
                'name'   => 'Unit kilogram to pound',
                'public' => true,
                'spec'   => '{"type":"unit","multiple":0,"access":"public","conversion":1,"metric":"kilogram","metric_measurement":1,"metric_annotation":"kg","imperial":"pound","imperial_measurement":2.20462,"imperial_annotation":"lbs"}',
            ],
            'unit_l_to_gal'                    => [
                'name'   => 'Unit liter to gallon',
                'public' => true,
                'spec'   => '{"type":"unit","multiple":0,"access":"public","conversion":1,"metric":"liter","metric_measurement":1,"metric_annotation":"l","imperial":"gallon","imperial_measurement":0.264172,"imperial_annotation":"gal"}',
            ],
            'unit_kgm2_to_lbsft2'              => [
                'name'   => 'Unit kilogram per square meter to pounds per square foot',
                'public' => true,
                'spec'   => '{"type":"unit","multiple":0,"access":"public","conversion":1,"metric":"kilogram per square meter","metric_measurement":1,"metric_annotation":"kg/m2","imperial":"pounds per square foot","imperial_measurement":0.204816,"imperial_annotation":"lbs/ft2"}',
            ],
            'text_without_translation'                           => [
                'name'   => 'Texte sans traduction',
                'public' => true,
                'spec'   => '{"type":"textWithoutTranslation","multiple":0,"access":"public"}',
            ],
            'keywords' => [
                'name'   => 'Mots clés',
                'public' => false,
                'spec'   => '{"type":"keywords","multiple":0,"access":"private"}',
            ],
        ];

        $types = [];

        foreach ($attributes_types as $code => $type) {
            $types[$code] = factory(AttributeType::class)->create(
                [
                    'code'   => $code,
                    'name'   => $type['name'],
                    'public' => $type['public'],
                    'specs'  => json_decode($type['spec']),
                ]
            );
        }

        return $types;
    }

    /**
     * Fait l'ajout des fields pour les produits parent du PIM
     */
    private function getBaseParentAttribute()
    {
        $functions = $this->getProductFunctions();
        $roles = $this->getProductRoles();
        $components = $this->getProductComponents();
        $test_norms_approbations = $this->getProductTestsNormsApprobations();
        $signatures = $this->getProductSignatures();

        return [
            [
                'name'                   => 'name',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('name'),
                'is_minimum_requirement' => true,
                'type'                   => 'text',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'name',
                    'values' => '{"fr":"Nom du produit", "en":"Product name"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_nature',
                'has_value'              => true,
                'values'                 => '{"fr":{"1":"Autres","2":"Feuille","3":"Liquide","4":"Panneau"},"en":{"1":"Others","2":"Sheet","3":"Liquid","4":"Panel"}}',
                'options'                => $this->getOptionJson('text_nature'),
                'is_minimum_requirement' => true,
                'type'                   => 'choice',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_nature',
                    'values' => '{"fr":"Nature du produit", "en":"Product nature"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_function',
                'has_value'              => true,
                'values'                 => json_encode(['fr' => $functions, 'en' => $functions], JSON_FORCE_OBJECT),
                'options'                => $this->getOptionJson('list_function'),
                'is_minimum_requirement' => true,
                'type'                   => 'choice_multiple_image_no_display',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_function',
                    'values' => '{"fr":"Fonction", "en":"Function"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'building_component',
                'has_value'              => true,
                'values'                 => json_encode(['fr' => $components, 'en' => $components], JSON_FORCE_OBJECT),
                'options'                => $this->getOptionJson('list_composante', null, true),
                'is_minimum_requirement' => true,
                'type'                   => 'choice_multiple',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'building_component',
                    'values' => '{"fr":"Composante du bâtiment", "en":"Building Component"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_role',
                'has_value'              => true,
                'values'                 => json_encode(['fr' => $roles, 'en' => $roles], JSON_FORCE_OBJECT),
                'options'                => $this->getOptionJson('list_type'),
                'is_minimum_requirement' => true,
                'type'                   => 'choice_multiple_image_no_display',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_function',
                    'values' => '{"fr":"Rôle du produit", "en":"Product roles"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_description',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('text_description'),
                'is_minimum_requirement' => true,
                'type'                   => 'textarea',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_description',
                    'values' => '{"fr":"Description commerciale", "en":"Commercial description"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_benefits',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('list_avantages', 3),
                'is_minimum_requirement' => false,
                'type'                   => 'text_multiple',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_benefits',
                    'values' => '{"fr":"Avantage", "en":"Benefits"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_image',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('file_image'),
                'is_minimum_requirement' => true,
                'type'                   => 'image',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_image',
                    'values' => '{"fr":"Photo du produit", "en":"Photo of the product"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_technical_sheet',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'files',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_technical_sheet',
                    'values' => '{"fr":"Fiche technique", "en":"Technical sheet"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_product_file',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'file',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_product_file',
                    'values' => '{"fr":"Fichier produit", "en":"Product file"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'complementary_product',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{"limit":3}',
                'is_minimum_requirement' => false,
                'type'                   => 'text_link_multiple',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'complementary_product',
                    'values' => '{"fr":"Produits complementaire", "en":"Complementary products"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_signature',
                'has_value'              => true,
                'values'                 => json_encode(['fr' => $signatures, 'en' => $signatures], JSON_FORCE_OBJECT),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_signature',
                    'values' => '{"fr":"Signature", "en":"Signature"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_test_norm_approbations',
                'has_value'              => true,
                'values'                 => json_encode(['fr' => $test_norms_approbations, 'en' => $test_norms_approbations], JSON_FORCE_OBJECT),
                'options'                => $this->getOptionJson('list_certifications'),
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple_image_no_display',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_test_norm_approbations',
                    'values' => '{"fr":"Test, Norme et Approbation", "en":"Test, Standard and Approbation"}',
                ],
                'nature'                 => ['F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_waterproofing',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Perméable à l\'air","1":"Perméable à la vapeur"},"en":{"0":"Perméable à l\'air","1":"Perméable à la vapeur"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_waterproofing',
                    'values' => '{"fr":"Perméabilité", "en":"Waterproofing"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_feature',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Faible odeur","1":"Sans COV","2":"Sans solvant"},"en":{"0":"Faible odeur","1":"Sans COV","2":"Sans solvant"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_feature',
                    'values' => '{"fr":"Caratéristique", "en":"Feature"}',
                ],
                'nature'                 => ['L'],
            ],
            [
                'name'                   => 'product_COV_content',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'number',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_COV_content',
                    'values' => '{"fr":"Teneur en COV", "en":"COV content"}',
                ],
                'nature'                 => ['L'],
            ],
            [
                'name'                   => 'product_armature',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Autre","1":"Composite","2":"Feuille d\'aluminum","3":"Polyester non tissé","4":"Voile de verre"},"en":{"0":"Other","1":"Composite","2":"Aluminium sheet","3":"Polyester non tissé","4":"Voile de verre"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_armature',
                    'values' => '{"fr":"Armature", "en":"Armature"}',
                ],
                'nature'                 => ['F', 'P'],
            ],
            [
                'name'                   => 'product_technology',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Autre","1":"Bitume modifié SBS","2":"Bitume Oxidé","3":"Epoxy","4":"EPS","5":"Fibre de bois","6":"Laine de roche","7":"Multi technologie","8":"PMMA","9":"Polyisocyanurate","10":"PU","11":"XPS"},"en":{"0":"Other","1":"Bitume modifié SBS","2":"Bitume Oxidé","3":"Epoxy","4":"EPS","5":"Fibre de bois","6":"Laine de roche","7":"Multi technologie","8":"PMMA","9":"Polyisocyanurate","10":"PU","11":"XPS"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_technology',
                    'values' => '{"fr":"Technologie", "en":"Technology"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_cleaning',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'text',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_cleaning',
                    'values' => '{"fr":"Nettoyage", "en":"Cleaning"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_storage',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Autre","1":"Palette","2":"Palette double"},"en":{"0":"Other","1":"Pallet","2":"Double Pallet"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_cleaning',
                    'values' => '{"fr":"Entreposage", "en":"Storage"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_foundation_type',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Blindside waterproofing","1":"Conventionnelle","2":"Insulated concrete forms"},"en":{"0":"Blindside waterproofing","1":"Conventionnelle","2":"Insulated concrete forms"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_foundation_type',
                    'values' => '{"fr":"Type de fondation", "en":"Foundation type"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_material_safety_data_sheet',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => true,
                'type'                   => 'file',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_material_safety_data_sheet',
                    'values' => '{"fr":"Fiche signalétique", "en":"Material safety data sheet"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_leed',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'file',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_leed',
                    'values' => '{"fr":"Fiche LEED", "en":"Leed file"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_qr_code',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'file',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_qr_code',
                    'values' => '{"fr":"Code QR", "en":"QR code"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_technical_bulletin',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'file',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_technical_bulletin',
                    'values' => '{"fr":"Bulletin technique", "en":"Technical Bulletin"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_installation_video',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'text_hyperlink',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_installation_video',
                    'values' => '{"fr":"Vidéo d\'installation", "en":"Installation video"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'product_promotional_video',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'text_hyperlink',
                'model_type'             => 'parent_product',
                'label'                  => [
                    'name'   => 'product_promotional_video',
                    'values' => '{"fr":"Vidéo promotionnel", "en":"Promotional Video"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'search_keywords',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{"special_index_key":"search_keywords"}',
                'is_minimum_requirement' => false,
                'is_parent_attribute'    => true,
                'should_index'           => false,
                'model_type'             => 'parent_product',
                'type'                   => 'keywords',
                'label'                  => [
                    'name'   => 'search_keywords',
                    'values' => '{"fr":"Mots clés de recherche","en":"Search keywords"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
        ];
    }

    /**
     * @return array
     */
    private function getChildAttributes()
    {
        return [
            [
                'name'                   => 'child_product_name',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('name'),
                'is_minimum_requirement' => true,
                'type'                   => 'text',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_name',
                    'values' => '{"fr":"Nom du produit", "en":"Product name"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_code',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => true,
                'type'                   => 'text_without_translation',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_code',
                    'values' => '{"fr":"Code produit", "en":"Product code"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_color',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Beige","1":"Blanc","2":"Blanc pur","3":"Blanc traffic","4":"Bleu","5":"Brun","6":"Chamois","7":"Fumée","8":"Granite","9":"Gris","10":"Gris pierre","11":"Gris silex","12":"Gris soncé","13":"Gris-vert","14":"incolore","15":"Noir","16":"Noir pâle","17":"Rouge","18":"Sienne","19":"Tabac","20":"Tan","21":"Terracotta","23":"Vert"},"en":{"0":"Beige","1":"Blanc","2":"Blanc pur","3":"Blanc traffic","4":"Bleu","5":"Brun","6":"Chamois","7":"Fumée","8":"Granite","9":"Gris","10":"Gris pierre","11":"Gris silex","12":"Gris soncé","13":"Gris-vert","14":"incolore","15":"Noir","16":"Noir pâle","17":"Rouge","18":"Sienne","19":"Tabac","20":"Tan","21":"Terracotta","23":"Vert"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_color',
                    'values' => '{"fr":"Couleur", "en":"Color"}',
                ],
                'nature'                 => ['F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_installation',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Adhésif","1":"Aérosol","2":"Applicateur à pile","3":"Applicateur DUOTACK 5 galons","4":"Autocollante","5":"Autre","6":"Bitume chaud","7":"Brosse","8":"Extrudeur","9":"Fixation mécanique","10":"Multi-application","11":"Pinceau","12":"Pistolet vaporisateur","13":"Racloir dentelé","14":"Rouleau","15":"Sans attache","16":"Thermosoudée","17":"Truelle"},"en":{"0":"Adhésif","1":"Aérosol","2":"Applicateur à pile","3":"Applicateur DUOTACK 5 galons","4":"Autocollante","5":"Autre","6":"Bitume chaud","7":"Brosse","8":"Extrudeur","9":"Fixation mécanique","10":"Multi-application","11":"Pinceau","12":"Pistolet vaporisateur","13":"Racloir dentelé","14":"Rouleau","15":"Sans attache","16":"Thermosoudée","17":"Truelle"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_installation',
                    'values' => '{"fr":"Mise en œuvre", "en":"Installation method"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_thickness',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'unit_mm_to_mil',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_thickness',
                    'values' => '{"fr":"Épaisseur", "en":"Thickness"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_length',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'unit_m_to_ft',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_length',
                    'values' => '{"fr":"Longueur", "en":"Length"}',
                ],
                'nature'                 => ['F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_width',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'unit_m_to_ft',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_width',
                    'values' => '{"fr":"Largeur", "en":"Width"}',
                ],
                'nature'                 => ['F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_number_per_pallet',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'number',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_number_per_pallet',
                    'values' => '{"fr":"Nombre par palette", "en":"Number per pallet"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_number_per_package',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'number',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_number_per_package',
                    'values' => '{"fr":"Quantité par paquet", "en":"Quantity per package"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_weight',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'unit_kg_to_lbs',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_number_per_pallet',
                    'values' => '{"fr":"Poids", "en":"Weight"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_surface',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Autre","1":"Armature de polyester","2":"Couche de fibre de verre saturé d\'asphalte","3":"Feuille d\'aluminium gauffré","4":"Fibre Minérales (fibre de roche)","5":"Fibre Minérales (fibre de roche) avec surface rigide","6":"Fibre Minérales (fibre de roche) de haute densité","7":"Fibre Minérales (fibre de roche) saturé d\'une couche de bitume","8":"Film plastique thermofusible","9":"Granule","10":"Granule de céramique hautement réfléchissante","11":"Papier kraft","12":"Papier organique renforcé par des fils de fibre de verre","13":"Pellicule multicouche blanche hautement réfléchissante","14":"Revêtement de fibre de verre","15":"Sable","16":"Tissé de polyéthylène trilaminaire"},"en":{"0":"Autre","1":"Armature de polyester","2":"Couche de fibre de verre saturé d\'asphalte","3":"Feuille d\'aluminium gauffré","4":"Fibre Minérales (fibre de roche)","5":"Fibre Minérales (fibre de roche) avec surface rigide","6":"Fibre Minérales (fibre de roche) de haute densité","7":"Fibre Minérales (fibre de roche) saturé d\'une couche de bitume","8":"Film plastique thermofusible","9":"Granule","10":"Granule de céramique hautement réfléchissante","11":"Papier kraft","12":"Papier organique renforcé par des fils de fibre de verre","13":"Pellicule multicouche blanche hautement réfléchissante","14":"Revêtement de fibre de verre","15":"Sable","16":"Tissé de polyéthylène trilaminaire"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_surface',
                    'values' => '{"fr":"Surface", "en":"Surface"}',
                ],
                'nature'                 => ['F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_under_face',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Autre","1":"Couche de fibre de verre saturé d\'aspjalte","2":"Fibre de bois ignifugée","3":"Fibre minérale (laine de roche) de haute densité","4":"Fibre minérale (laine de roche)","5":"Film plastique thermofusible","6":"Film siliconé détachable \"split back\"","7":"Film siliconé détachable","8":"Gypse","9":"Isolant de polyisocyanurate","10":"Panneau asphaltique","11":"Papier kraft","12":"Papier organique renforcé par des fils de fibre de verre","13":"Polyisocyanurate HD","14":"Revêtement de fibre de verre","15":"Sable"},"en":{"0":"Autre","1":"Couche de fibre de verre saturé d\'asphalte","2":"Fibre de bois ignifugée","3":"Fibre minérale (laine de roche) de haute densité","4":"Fibre minérale (laine de roche)","5":"Film plastique thermofusible","6":"Film siliconé détachable \"split back\"","7":"Film siliconé détachable","8":"Gypse","9":"Isolant de polyisocyanurate","10":"Panneau asphaltique","11":"Papier kraft","12":"Papier organique renforcé par des fils de fibre de verre","13":"Polyisocyanurate HD","14":"Revêtement de fibre de verre","15":"Sable"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_surface',
                    'values' => '{"fr":"Sous-face", "en":"Under face"}',
                ],
                'nature'                 => ['F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_temperature_grade',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Autre","1":"Basse température","2":"Été","3":"Haute température","4":"Hiver"},"en":{"0":"Autre","1":"Basse température","2":"Été","3":"Haute température","4":"Hiver"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_temperature_grade',
                    'values' => '{"fr":"Grade température", "en":"Grade température"}',
                ],
                'nature'                 => ['A', 'F', 'L', 'P'],
            ],
            [
                'name'                   => 'child_product_fire_grade',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Fr"},"en":{"0":"Fr"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_fire_grade',
                    'values' => '{"fr":"Grade feu", "en":"Fire grade"}',
                ],
                'nature'                 => ['L', 'P'],
            ],
            [
                'name'                   => 'child_product_application_temperature',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'unit_celsius',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_application_temperature',
                    'values' => '{"fr":"Température d\'application", "en":"Application temperature"}',
                ],
                'nature'                 => ['F', 'L'],
            ],
            [
                'name'                   => 'child_product_service_temperature',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'unit_celsius',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_service_temperature',
                    'values' => '{"fr":"Température de service", "en":"Service temperature"}',
                ],
                'nature'                 => ['F', 'L'],
            ],
            [
                'name'                   => 'child_product_gallon_width',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'unit_mm_to_in',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_gallon_width',
                    'values' => '{"fr":"Largeur du galon", "en":"Gallon width"}',
                ],
                'nature'                 => ['F', 'P'],
            ],
            [
                'name'                   => 'child_product_gross_area',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'unit_m2_to_ft2',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_gross_area',
                    'values' => '{"fr":"Superficie brute", "en":"Gross area"}',
                ],
                'nature'                 => ['A', 'P', 'F', 'L'],
            ],
            [
                'name'                   => 'child_product_net_area',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'unit_m2_to_ft2',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_net_area',
                    'values' => '{"fr":"Superficie nette", "en":"Net area"}',
                ],
                'nature'                 => ['A', 'P', 'F', 'L'],
            ],
            [
                'name'                   => 'child_product_volume',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'unit_l_to_gal',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_volume',
                    'values' => '{"fr":"Volume", "en":"Volume"}',
                ],
                'nature'                 => ['L'],
            ],
            [
                'name'                   => 'child_product_consumption',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'unit_kgm2_to_lbsft2',
                'model_type'             => 'child_product',
                'label'                  => [
                    'name'   => 'child_product_consumption',
                    'values' => '{"fr":"Consommation", "en":"Consumption"}',
                ],
                'nature'                 => ['L'],
            ],
        ];
    }

    private function getSpecificationAttributes()
    {
        return [
            // Nom du devis
            [
                'name'                   => 'spec_name',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('name'),
                'is_minimum_requirement' => true,
                'type'                   => 'text',
                'model_type'             => 'specification',
                'label'                  => [
                    'name'   => 'spec_name',
                    'values' => '{"fr":"Nom du devis", "en":"Specification name"}',
                ],
            ],
            // Composante du batiment
            [
                'name'                   => 'spec_building_component',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Toits","1":"Fondations","2":"Murs","3":"Ponts","4":"Stationnements","5":"Balcons et Terrasses","6":"Fontaines et Bassins","7":"Applications Intérieures"},"en":{"0":"Roofs","1":"Foundations","2":"Walls","3":"Bridges","4":"Parking decks","5":"Balconies and Plaza decks","6":"Fountains and Ponds","7":"Indoor Applications"}}',
                'options'                => '{}',
                'is_minimum_requirement' => true,
                'type'                   => 'choice_multiple',
                'model_type'             => 'specification',
                'label'                  => [
                    'name'   => 'spec_building_component',
                    'values' => '{"fr":"Composante du bâtiment", "en":"Building Component"}',
                ],
            ],
            // Methode d'installation
            [
                'name'                   => 'spec_installation_method',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Autocollant","1":"Fixé mécaniquement","2":"Panneaux composites","3":"Pleine adhérence","4":"Semi-adhérence","5":"Thermosoudé","6":"Adhésif à froid","7":"Bitume chaud SEBS/oxydé","8":"Soprabase","9":"Par plots d\'adhésif","10":"Multi II"},"en":{"0":"Autocollant","1":"Fixé mécaniquement","2":"Panneaux composites","3":"Pleine adhérence","4":"Semi - adhérence","5":"Thermosoudé","6":"Adhésif à froid","7":"Bitume chaud SEBS / oxydé","8":"Soprabase","9":"Par plots d\'adhésif","10":"Multi II"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'specification',
                'label'                  => [
                    'name'   => 'spec_installation_method',
                    'values' => '{"fr":"Méthode d\'installation", "en":"Installation method"}',
                ],
            ],
            // Fonction
            [
                'name'                   => 'spec_function',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":{"name":"Étanchété","image":"#"},"1":{"name":"Isolation","image":"#"},"2":{"name":"Insonorisation","image":"#"},"3":{"name":"Végétalisation","image":"#"},"4":{"name":"Compléments","image":"#"}},"en":{"0":{"name":"Waterproofing","image":"#"},"1":{"name":"Insulation","image":"#"},"2":{"name":"Soundproofing","image":"#"},"3":{"name":"Vegetative Solutions","image":"#"},"4":{"name":"Complementary Products","image":"#"}}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_image_no_display',
                'model_type'             => 'specification',
                'label'                  => [
                    'name'   => 'spec_function',
                    'values' => '{"fr":"Fonction", "en":"Function"}',
                ],
            ],
            // Produit Parent
            [
                'name'                   => 'spec_parent_product',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'text_link_multiple',
                'model_type'             => 'specification',
                'label'                  => [
                    'name'   => 'spec_parent_product',
                    'values' => '{"fr":"Produit parent","en":"Parent product"}',
                ],
            ],
            //Role du produit
            [
                'name'                   => 'spec_product_role',
                'has_value'              => false,
                'values'                 => '{{"fr":{"0":{"name":"Accessoire","image":"#"},"1":{"name":"Adhésif","image":"#"},"2":{"name":"Barrière anti-racine","image":"#"},"3":{"name":"Sous-couche","image":"#"},"4":{"name":"Membrane de finition","image":"#"},"5":{"name":"Enduit","image":"#"},"6":{"name":"Panneau composite","image":"#"},"7":{"name":"Panneau de support","image":"#"},"8":{"name":"Panneau de drainage","image":"#"},"9":{"name":"Substrat de croissance","image":"#"},"10":{"name":"Isolant","image":"#"},"11":{"name":"Membrane liquide","image":"#"},"12":{"name":"Mastic","image":"#"},"13":{"name":"Membrane","image":"#"},"14":{"name":"Apprêt","image":"#"},"15":{"name":"Membrane PVC","image":"#"},"16":{"name":"Scellant","image":"#"},"17":{"name":"Barrière thermique","image":"#"},"18":{"name":"Membrane TPO","image":"#"},"19":{"name":"Sous-toiture","image":"#"},"20":{"name":"Pare-vapeur","image":"#"}},"en":{"0":{"name":"Accessory","image":"#"},"1":{"name":"Adhesive","image":"#"},"2":{"name":"Anti-root barrier","image":"#"},"3":{"name":"Base sheet","image":"#"},"4":{"name":"Cap sheet","image":"#"},"5":{"name":"Coating","image":"#"},"6":{"name":"Composite board","image":"#"},"7":{"name":"Cover board","image":"#"},"8":{"name":"Drainage board","image":"#"},"9":{"name":"Growing medium","image":"#"},"10":{"name":"Insulation","image":"#"},"11":{"name":"Liquid membrane","image":"#"},"12":{"name":"Mastic","image":"#"},"13":{"name":"Membrane","image":"#"},"14":{"name":"Primer","image":"#"},"15":{"name":"PVC Membrane","image":"#"},"16":{"name":"Sealant","image":"#"},"17":{"name":"Thermal barrier","image":"#"},"18":{"name":"TPO Membrane","image":"#"},"19":{"name":"Underlayment","image":"#"},"20":{"name":"Vapor barrier","image":"#"}}}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'specification',
                'label'                  => [
                    'name'   => 'spec_product_role',
                    'values' => '{"fr":"Rôle du poduit","en":"Product Role"}',
                ],
            ],
            //Fiche de détails
            [
                'name'                   => 'spec_sheet',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('file'),
                'is_minimum_requirement' => true,
                'type'                   => 'file',
                'model_type'             => 'specification',
                'label'                  => [
                    'name'   => 'spec_sheet',
                    'values' => '{"fr":"Fiche de détails", "en":"Details sheet"}',
                ],
            ],
        ];
    }

    private function getDetailAttributes()
    {
        return [
            // Nom du détails
            [
                'name'                   => 'detail_name',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('name'),
                'is_minimum_requirement' => true,
                'type'                   => 'text',
                'model_type'             => 'detail',
                'label'                  => [
                    'name'   => 'detail_name',
                    'values' => '{"fr":"Nom du détail", "en":"Detail name"}',
                ],
            ],
            // Composante du batiment
            [
                'name'                   => 'detail_building_component',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Toits","1":"Fondations","2":"Murs","3":"Ponts","4":"Stationnements","5":"Balcons et Terrasses","6":"Fontaines et Bassins","7":"Applications Intérieures"},"en":{"0":"Roofs","1":"Foundations","2":"Walls","3":"Bridges","4":"Parking decks","5":"Balconies and Plaza decks","6":"Fountains and Ponds","7":"Indoor Applications"}}',
                'options'                => '{}',
                'is_minimum_requirement' => true,
                'type'                   => 'choice_multiple',
                'model_type'             => 'detail',
                'label'                  => [
                    'name'   => 'detail_building_component',
                    'values' => '{"fr":"Composante du bâtiment", "en":"Building Component"}',
                ],
            ],
            // Methode d'installation
            [
                'name'                   => 'detail_installation_method',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Autocollant","1":"Fixé mécaniquement","2":"Panneaux composites","3":"Pleine adhérence","4":"Semi-adhérence","5":"Thermosoudé","6":"Adhésif à froid","7":"Bitume chaud SEBS/oxydé","8":"Soprabase","9":"Par plots d\'adhésif","10":"Multi II"},"en":{"0":"Autocollant","1":"Fixé mécaniquement","2":"Panneaux composites","3":"Pleine adhérence","4":"Semi - adhérence","5":"Thermosoudé","6":"Adhésif à froid","7":"Bitume chaud SEBS / oxydé","8":"Soprabase","9":"Par plots d\'adhésif","10":"Multi II"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'detail',
                'label'                  => [
                    'name'   => 'detail_installation_method',
                    'values' => '{"fr":"Méthode d\'installation", "en":"Installation method"}',
                ],
            ],
            //Endroit de pose
            [
                'name'                   => 'detail_installation_location',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"À l\'évent","1":"À la jonction"},"en":{"0":"À l\'évent(En)","1":"À la jonction(En)"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'detail',
                'label'                  => [
                    'name'   => 'detail_installation_location',
                    'values' => '{"fr":"Endroit de pose", "en":"Installation location"}',
                ],
            ],
            //Famille de produit
            [
                'name'                   => 'detail_product_family',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Sopranature","1":"Sopraseal","2":"Colphène","3":"Antirock","4":"Trafikrock","5":"Alsan"},"en":{"0":"Sopranature","1":"Sopraseal","2":"Colphène","3":"Antirock","4":"Trafikrock","5":"Alsan"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'detail',
                'label'                  => [
                    'name'   => 'detail_product_family',
                    'values' => '{"fr":"Famille de produit", "en":"Product family"}',
                ],
            ],
            //Fiche de détails
            [
                'name'                   => 'detail_sheet',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('file'),
                'is_minimum_requirement' => true,
                'type'                   => 'files',
                'model_type'             => 'detail',
                'label'                  => [
                    'name'   => 'product_technical_sheet',
                    'values' => '{"fr":"Fiche de détails", "en":"Details sheet"}',
                ],
            ],
        ];
    }

    private function getTechnicalBulletinAttributes()
    {
        return [
            // Nom du bulletin technique
            [
                'name'                   => 'technical_bulletin_name',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('name'),
                'is_minimum_requirement' => true,
                'type'                   => 'text',
                'model_type'             => 'technical_bulletin',
                'label'                  => [
                    'name'   => 'technical_bulletin_name',
                    'values' => '{"fr":"Nom du bulletin technique", "en":"Technical bulletin name"}',
                ],
            ],
            // Composante du bâtiment
            [
                'name'                   => 'technical_bulletin_building_component',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Toits","1":"Fondations","2":"Murs","3":"Ponts","4":"Stationnements","5":"Balcons et Terrasses","6":"Fontaines et Bassins","7":"Applications Intérieures"},"en":{"0":"Roofs","1":"Foundations","2":"Walls","3":"Bridges","4":"Parking decks","5":"Balconies and Plaza decks","6":"Fountains and Ponds","7":"Indoor Applications"}}',
                'options'                => '{}',
                'is_minimum_requirement' => true,
                'type'                   => 'choice_multiple',
                'model_type'             => 'technical_bulletin',
                'label'                  => [
                    'name'   => 'technical_bulletin_building_component',
                    'values' => '{"fr":"Composante du bâtiment", "en":"Building Component"}',
                ],
            ],
            // Fonction
            [
                'name'                   => 'technical_bulletin_function',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":{"name":"Étanchété","image":"#"},"1":{"name":"Isolation","image":"#"},"2":{"name":"Insonorisation","image":"#"},"3":{"name":"Végétalisation","image":"#"},"4":{"name":"Compléments","image":"#"}},"en":{"0":{"name":"Waterproofing","image":"#"},"1":{"name":"Insulation","image":"#"},"2":{"name":"Soundproofing","image":"#"},"3":{"name":"Vegetative Solutions","image":"#"},"4":{"name":"Complementary Products","image":"#"}}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_image_no_display',
                'model_type'             => 'technical_bulletin',
                'label'                  => [
                    'name'   => 'technical_bulletin_function',
                    'values' => '{"fr":"Fonction", "en":"Function"}',
                ],
            ],
            // Produit Parent
            [
                'name'                   => 'technical_bulletin_parent_product',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'text_link_multiple',
                'model_type'             => 'technical_bulletin',
                'label'                  => [
                    'name'   => 'technical_bulletin_parent_product',
                    'values' => '{"fr":"Produit parent","en":"Parent product"}',
                ],
            ],
            // Méthodes d'installation
            [
                'name'                   => 'technical_bulletin_installation_method',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"Autocollant","1":"Fixé mécaniquement","2":"Panneaux composites","3":"Pleine adhérence","4":"Semi-adhérence","5":"Thermosoudé","6":"Adhésif à froid","7":"Bitume chaud SEBS/oxydé","8":"Soprabase","9":"Par plots d\'adhésif","10":"Multi II"},"en":{"0":"Autocollant","1":"Fixé mécaniquement","2":"Panneaux composites","3":"Pleine adhérence","4":"Semi - adhérence","5":"Thermosoudé","6":"Adhésif à froid","7":"Bitume chaud SEBS / oxydé","8":"Soprabase","9":"Par plots d\'adhésif","10":"Multi II"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'technical_bulletin',
                'label'                  => [
                    'name'   => 'technical_bulletin_installation_method',
                    'values' => '{"fr":"Méthode d\'installation", "en":"Installation method"}',
                ],
            ],
            //Endroit de pose
            [
                'name'                   => 'technical_bulletin_installation_location',
                'has_value'              => true,
                'values'                 => '{"fr":{"0":"À l\'évent","1":"À la jonction"},"en":{"0":"À l\'évent(En)","1":"À la jonction(En)"}}',
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple',
                'model_type'             => 'technical_bulletin',
                'label'                  => [
                    'name'   => 'technical_bulletin_installation_location',
                    'values' => '{"fr":"Endroit de pose", "en":"Installation location"}',
                ],
            ],
            //Fiche de détails
            [
                'name'                   => 'technical_bulletin_file',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('file'),
                'is_minimum_requirement' => true,
                'type'                   => 'files',
                'model_type'             => 'technical_bulletin',
                'label'                  => [
                    'name'   => 'technical_bulletin_file',
                    'values' => '{"fr":"Fichiers de bulletin technique", "en":"Technical bulletin files"}',
                ],
            ],
        ];
    }

    /**
     * Get system attributes
     *
     * @return array
     */
    private function getSystemAttributes()
    {
        $components = $this->getProductComponents();

        $roofing_system_type = [
            'fr' => [
                'Ballasté',
                'Conventionnel',
                'Inversé',
                'Ventillé',
            ],
            'en' => [
                'Ballasté',
                'Conventionnel',
                'Inversé',
                'Ventillé',
            ],
        ];

        $system_guaranties = [
            'fr' => [
                '5 ans',
                '10 ans',
                '15 ans',
                '20 ans',
                '5 ans matériaux',
                '10 ans matériaux',
                'Privilège',
                'Platinum',
                'Sopranature',
            ],
            'en' => [
                '5 years',
                '10 years',
                '15 years',
                '20 years',
                '5 years on materials',
                '10 years on materials',
                'Privilege',
                'Platinum',
                'Sopranature',
            ],
        ];

        $system_test_and_norm = [
            'fr' => $this->getProductTestsNormsApprobations(),
            'en' => $this->getProductTestsNormsApprobations(),
        ];

        $system_roof_slope = [
            'fr' => [
                'Faible',
                'Raide',
            ],
            'en' => [
                'Faible',
                'Raide',
            ],
        ];

        $system_installation_method = [
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
            ],
        ];

        $system_application_method = [
            'fr' => [
                'Classique',
                'Pré-appliquée',
            ],
            'en' => [
                'Classique',
                'Pré-appliquée',
            ],
        ];

        $system_insulation = [
            'fr' => [
                'Avec isolant',
                'Sans isolant',
            ],
            'en' => [
                'Avec isolant',
                'Sans isolant',
            ],
        ];

        $system_bool = [
            'fr' => [
                'Oui',
                'Non',
            ],
            'en' => [
                'Oui',
                'Non',
            ],
        ];

        $system_bridging = [
            'fr' => [
                'Acier',
                'Béton',
                'Béton projeté',
                'Bloc de maçonnerie',
                'Bois (contreplaqué)',
                'Bois (planche)',
                'Bois (traité)',
                'Boisage',
                'Coffrage isolé',
                'Colombage d\'acier',
                'Colombage de bois',
                'Dalle de propreté',
                'Gypse Extérieur',
                'Pierre',
                'Sol préparé',
            ],
            'en' => [
                'Acier',
                'Béton',
                'Béton projeté',
                'Bloc de maçonnerie',
                'Bois (contreplaqué)',
                'Bois (planche)',
                'Bois (traité)',
                'Boisage',
                'Coffrage isolé',
                'Colombage d\'acier',
                'Colombage de bois',
                'Dalle de propreté',
                'Gypse Extérieur',
                'Pierre',
                'Sol préparé',
            ],
        ];

        $system_family_wall = [
            'fr' => [
                'Pare-aire / Pare-vapeur',
                'Pare-air / Perméable à la vapeur d\'eau',
            ],
            'en' => [
                'Pare-aire / Pare-vapeur',
                'Pare-air / Perméable à la vapeur d\'eau',
            ],
        ];

        $system_family_foundation = [
            'fr' => [
                'Fondation avec drainage approprié à drain agricole (Cuvelage)',
                'Fondation avec drainage approprié à drain agricole (Murs)',
                'Fondation sous le niveau de la nappe phréatique / zone inondable / pas de drainage / pression hydrostatique élevée',
            ],
            'en' => [
                'Fondation avec drainage approprié à drain agricole (Cuvelage)',
                'Fondation avec drainage approprié à drain agricole (Murs)',
                'Fondation sous le niveau de la nappe phréatique / zone inondable / pas de drainage / pression hydrostatique élevée',
            ],
        ];

        $system_family_parking = [
            'fr' => [
                'Stationnement au-dessus d\'un espace habitable',
                'Stationnement dalle sur le sol',
            ],
            'en' => [
                'Stationnement au-dessus d\'un espace habitable',
                'Stationnement dalle sur le sol',
            ],
        ];

        $system_membrane_type = [
            'fr' => [
                'Liquide (PU)',
                'Liquid (PMMA)',
                'Produit en feuille',
            ],
            'en' => [
                'Liquide (PU)',
                'Liquid (PMMA)',
                'Produit en feuille',
            ],
        ];

        $attributes = [
            [
                'name'                   => 'system_name',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('name'),
                'is_minimum_requirement' => true,
                'type'                   => 'text',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_name',
                    'values' => '{"fr":"Nom du système", "en":"System name"}',
                ],
            ],
            [
                'name'                   => 'building_component',
                'has_value'              => true,
                'values'                 => json_encode(['fr' => $components, 'en' => $components], JSON_FORCE_OBJECT),
                'options'                => $this->getOptionJson('list_composante', null, true),
                'is_minimum_requirement' => true,
                'type'                   => 'choice_multiple',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'building_component',
                    'values' => '{"fr":"Composante du bâtiment", "en":"Building Component"}',
                ],
            ],
            [
                'name'                   => 'system_benefit',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('list_avantages'),
                'is_minimum_requirement' => true,
                'type'                   => 'text_multiple',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_benefit',
                    'values' => '{"fr":"Avantage", "en":"Benefit"}',
                ],
            ],
            [
                'name'                   => 'system_3d_model',
                'has_value'              => false,
                'values'                 => null,
                'options'                => $this->getOptionJson('image_3d'),
                'is_minimum_requirement' => true,
                'type'                   => 'image',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_3d_model',
                    'values' => '{"fr":"Modèle 3D", "en":"3D model"}',
                ],
            ],
            [
                'name'                   => 'system_guaranty',
                'has_value'              => true,
                'values'                 => json_encode($system_guaranties),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_guaranty',
                    'values' => '{"fr":"Garanties", "en":"Guaranties"}',
                ],
            ],
            [
                'name'                   => 'system_installation_video',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'text_hyperlink',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_installation_video',
                    'values' => '{"fr":"Vidéo d\'installation", "en":"Installation video"}',
                ],
            ],

            //Complementary Product
            [
                'name'                   => 'system_complementary_product',
                'has_value'              => false,
                'values'                 => null,
                'options'                => '{"limit":3}',
                'is_minimum_requirement' => false,
                'type'                   => 'text_link_multiple',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_complementary_product',
                    'values' => '{"fr":"Produits complementaire", "en":"Complementary products"}',
                ],
            ],

            //Tests, Normes et Approbations
            [
                'name'                   => 'system_test_norms_approbations',
                'has_value'              => true,
                'values'                 => json_encode($system_test_and_norm),
                'options'                => $this->getOptionJson('list_certifications'),
                'is_minimum_requirement' => false,
                'type'                   => 'choice_multiple_image_no_display',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_test_norms_approbations',
                    'values' => '{"fr":"Tests, Normes et Approbations", "en":"Tests norms and approbations"}',
                ],
            ],

            //////////////////////////////////////////////////////
            //                                                  //
            //                  Roofing System                  //
            //                                                  //
            //////////////////////////////////////////////////////

            //type de system
            [
                'name'                   => 'system_' . self::ROOF_SYSTEM . '_type',
                'has_value'              => true,
                'values'                 => json_encode($roofing_system_type),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::ROOF_SYSTEM . '_type',
                    'values' => '{"fr":"Type de système", "en":"System type"}',
                ],
            ],

            //Métode d'installation
            [
                'name'                   => 'system_' . self::ROOF_SYSTEM . '_installation_method',
                'has_value'              => true,
                'values'                 => json_encode($system_installation_method),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::ROOF_SYSTEM . '_installation_method',
                    'values' => '{"fr":"Métode d\'installation", "en":"Installation method"}',
                ],
            ],

            //type de pente
            [
                'name'                   => 'system_' . self::ROOF_SYSTEM . '_roof_slope',
                'has_value'              => true,
                'values'                 => json_encode($system_roof_slope),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::ROOF_SYSTEM . '_roof_slope',
                    'values' => '{"fr":"Type de pente", "en":"Roof slope type"}',
                ],
            ],

            //isolation
            [
                'name'                   => 'system_' . self::ROOF_SYSTEM . '_insulation',
                'has_value'              => true,
                'values'                 => json_encode($system_insulation),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::ROOF_SYSTEM . '_insulation',
                    'values' => '{"fr":"Isolation", "en":"Insulation"}',
                ],
            ],

            //Pontage
            [
                'name'                   => 'system_' . self::ROOF_SYSTEM . '_bridging',
                'has_value'              => true,
                'values'                 => json_encode($system_bridging),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::ROOF_SYSTEM . '_bridging',
                    'values' => '{"fr":"Pontage", "en":"Bridging"}',
                ],
            ],

            //haute performance
            [
                'name'                   => 'system_' . self::ROOF_SYSTEM . '_high_performance',
                'has_value'              => true,
                'values'                 => json_encode($system_bool),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::ROOF_SYSTEM . '_high_performance',
                    'values' => '{"fr":"Haute Performance", "en":"High Performance"}',
                ],
            ],

            //////////////////////////////////////////////////////
            //                                                  //
            //                  Parking System                  //
            //                                                  //
            //////////////////////////////////////////////////////

            //Family type
            [
                'name'                   => 'system_' . self::PARKING_SYSTEM . '_family',
                'has_value'              => true,
                'values'                 => json_encode($system_family_parking),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::PARKING_SYSTEM . '_family',
                    'values' => '{"fr":"Famille du système", "en":"System family"}',
                ],
            ],

            //Type de membrane
            [
                'name'                   => 'system_' . self::PARKING_SYSTEM . '_membrane_type',
                'has_value'              => true,
                'values'                 => json_encode($system_membrane_type),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::PARKING_SYSTEM . '_membrane_type',
                    'values' => '{"fr":"Type de membrane", "en":"Membrane type"}',
                ],
            ],

            //Pontage
            [
                'name'                   => 'system_' . self::PARKING_SYSTEM . '_bridging',
                'has_value'              => true,
                'values'                 => json_encode($system_bridging),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::PARKING_SYSTEM . '_bridging',
                    'values' => '{"fr":"Pontage", "en":"Bridging"}',
                ],
            ],

            //////////////////////////////////////////////////////
            //                                                  //
            //                  Bridge System                   //
            //                                                  //
            //////////////////////////////////////////////////////

            //Type de membrane
            [
                'name'                   => 'system_' . self::BRIDGE_SYSTEM . '_membrane_type',
                'has_value'              => true,
                'values'                 => json_encode($system_membrane_type),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::BRIDGE_SYSTEM . '_membrane_type',
                    'values' => '{"fr":"Type de membrane", "en":"Membrane type"}',
                ],
            ],

            //Pontage
            [
                'name'                   => 'system_' . self::BRIDGE_SYSTEM . '_bridging',
                'has_value'              => true,
                'values'                 => json_encode($system_bridging),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::BRIDGE_SYSTEM . '_bridging',
                    'values' => '{"fr":"Pontage", "en":"Bridging"}',
                ],
            ],

            //////////////////////////////////////////////////////
            //                                                  //
            //                  Balcony System                  //
            //                                                  //
            //////////////////////////////////////////////////////

            //Type de membrane
            [
                'name'                   => 'system_' . self::BALCONY_SYSTEM . '_membrane_type',
                'has_value'              => true,
                'values'                 => json_encode($system_membrane_type),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::BALCONY_SYSTEM . '_membrane_type',
                    'values' => '{"fr":"Type de membrane", "en":"Membrane type"}',
                ],
            ],

            //Pontage
            [
                'name'                   => 'system_' . self::BALCONY_SYSTEM . '_bridging',
                'has_value'              => true,
                'values'                 => json_encode($system_bridging),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::BALCONY_SYSTEM . '_bridging',
                    'values' => '{"fr":"Pontage", "en":"Bridging"}',
                ],
            ],

            //////////////////////////////////////////////////////
            //                                                  //
            //                Foundation System                 //
            //                                                  //
            //////////////////////////////////////////////////////

            //Family type
            [
                'name'                   => 'system_' . self::FOUNDATION_SYSTEM . '_family',
                'has_value'              => true,
                'values'                 => json_encode($system_family_foundation),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::FOUNDATION_SYSTEM . '_family',
                    'values' => '{"fr":"Famille du système", "en":"System family"}',
                ],
            ],

            //Métode d'installation
            [
                'name'                   => 'system_' . self::FOUNDATION_SYSTEM . '_installation_method',
                'has_value'              => true,
                'values'                 => json_encode($system_installation_method),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::FOUNDATION_SYSTEM . '_installation_method',
                    'values' => '{"fr":"Métode d\'installation", "en":"Installation method"}',
                ],
            ],

            //Métode d'applications
            [
                'name'                   => 'system_' . self::FOUNDATION_SYSTEM . '_application_method',
                'has_value'              => true,
                'values'                 => json_encode($system_application_method),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::FOUNDATION_SYSTEM . '_application_method',
                    'values' => '{"fr":"Métode d\'application", "en":"Application method"}',
                ],
            ],

            //Substrat
            [
                'name'                   => 'system_' . self::FOUNDATION_SYSTEM . '_bridging',
                'has_value'              => true,
                'values'                 => json_encode($system_bridging),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::FOUNDATION_SYSTEM . '_bridging',
                    'values' => '{"fr":"Substrat", "en":"Substrate"}',
                ],
            ],

            //haute performance
            [
                'name'                   => 'system_' . self::FOUNDATION_SYSTEM . '_high_performance',
                'has_value'              => true,
                'values'                 => json_encode($system_bool),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::FOUNDATION_SYSTEM . '_high_performance',
                    'values' => '{"fr":"Haute Performance", "en":"High Performance"}',
                ],
            ],

            //////////////////////////////////////////////////////
            //                                                  //
            //                   Wall System                    //
            //                                                  //
            //////////////////////////////////////////////////////

            //Family type
            [
                'name'                   => 'system_' . self::WALL_SYSTEM . '_family',
                'has_value'              => true,
                'values'                 => json_encode($system_family_wall),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::WALL_SYSTEM . '_family',
                    'values' => '{"fr":"Famille du système", "en":"System family"}',
                ],
            ],

            //Métode d'installation
            [
                'name'                   => 'system_' . self::WALL_SYSTEM . '_installation_method',
                'has_value'              => true,
                'values'                 => json_encode($system_installation_method),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::WALL_SYSTEM . '_installation_method',
                    'values' => '{"fr":"Métode d\'installation", "en":"Installation method"}',
                ],
            ],

            //Substrat
            [
                'name'                   => 'system_' . self::WALL_SYSTEM . '_bridging',
                'has_value'              => true,
                'values'                 => json_encode($system_bridging),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::WALL_SYSTEM . '_bridging',
                    'values' => '{"fr":"Substrat", "en":"Substrate"}',
                ],
            ],

            //////////////////////////////////////////////////////
            //                                                  //
            //                   Plaza System                   //
            //                                                  //
            //////////////////////////////////////////////////////

            //Type de membrane
            [
                'name'                   => 'system_' . self::PLAZA_DECK_SYSTEM . '_membrane_type',
                'has_value'              => true,
                'values'                 => json_encode($system_membrane_type),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::PLAZA_DECK_SYSTEM . '_membrane_type',
                    'values' => '{"fr":"Type de membrane", "en":"Membrane type"}',
                ],
            ],

            //Substrat
            [
                'name'                   => 'system_' . self::PLAZA_DECK_SYSTEM . '_bridging',
                'has_value'              => true,
                'values'                 => json_encode($system_bridging),
                'options'                => '{}',
                'is_minimum_requirement' => false,
                'type'                   => 'choice',
                'model_type'             => 'system',
                'label'                  => [
                    'name'   => 'system_' . self::PLAZA_DECK_SYSTEM . '_bridging',
                    'values' => '{"fr":"Substrat", "en":"Substrate"}',
                ],
            ],
        ];

        return $attributes;
    }

    /**
     * @return array
     */
    private function getProductRoles()
    {
        return [
            ['name' => 'Accessoire', 'image' => '#'],
            ['name' => 'Adhésif', 'image' => '#'],
            ['name' => 'Apprêt', 'image' => '#'],
            ['name' => 'Attaches mécaniques', 'image' => '#'],
            ['name' => 'Bande de recouvrement', 'image' => '#'],
            ['name' => 'Barrière anti-racine', 'image' => '#'],
            ['name' => 'Barrière thermique', 'image' => '#'],
            ['name' => 'Dispositif de sortie de toiture', 'image' => '#'],
            ['name' => 'Drain', 'image' => '#'],
            ['name' => 'Enduit', 'image' => '#'],
            ['name' => 'Équipement', 'image' => '#'],
            ['name' => 'Évent', 'image' => '#'],
            ['name' => 'Géotextile', 'image' => '#'],
            ['name' => 'Insonorisation', 'image' => '#'],
            ['name' => 'Isolant', 'image' => '#'],
            ['name' => 'Isolant de pente', 'image' => '#'],
            ['name' => 'Joint de dilatation', 'image' => '#'],
            ['name' => 'Mastic', 'image' => '#'],
            ['name' => 'Matelat capillaire', 'image' => '#'],
            ['name' => 'Membrane d\'étanchéité', 'image' => '#'],
            ['name' => 'Membrane de finition', 'image' => '#'],
            ['name' => 'Membrane de relevé', 'image' => '#'],
            ['name' => 'Membrane de transition', 'image' => '#'],
            ['name' => 'Membrane de sous-couche', 'image' => '#'],
            ['name' => 'Membrane de sous-toiture', 'image' => '#'],
            ['name' => 'Membrane intramurale', 'image' => '#'],
            ['name' => 'Membrane liquide', 'image' => '#'],
            ['name' => 'Panneau de composite', 'image' => '#'],
            ['name' => 'Panneau de drainage', 'image' => '#'],
            ['name' => 'Panneau de recouvrement', 'image' => '#'],
            ['name' => 'Panneau de réfection', 'image' => '#'],
            ['name' => 'Panneau de support', 'image' => '#'],
            ['name' => 'Panneau isolant', 'image' => '#'],
            ['name' => 'Pare-air', 'image' => '#'],
            ['name' => 'Pare-vapeur', 'image' => '#'],
            ['name' => 'Pâte d\'étanchéité', 'image' => '#'],
            ['name' => 'Protecteur d\'avant-toit', 'image' => '#'],
            ['name' => 'Scellant', 'image' => '#'],
            ['name' => 'Substrat de croissance', 'image' => '#'],
        ];
    }

    /**
     * @return array
     */
    private function getProductFunctions()
    {
        return [
            ['name' => 'Étanchéité', 'image' => '#'],
            ['name' => 'Isolation', 'image' => '#'],
            ['name' => 'Insonorisation', 'image' => '#'],
            ['name' => 'Végétalisation', 'image' => '#'],
            ['name' => 'Compléments', 'image' => '#'],
        ];
    }

    /**
     * @return array
     */
    private function getProductComponents()
    {
        return [
            'Toits',
            'Fondations',
            'Murs',
            'Ponts',
            'Stationnements',
            'Balcons et Terrasses',
            'Fontaines et Bassins',
            'Applications Intérieures',
        ];
    }

    /**
     * @return array
     */
    private function getProductTestsNormsApprobations()
    {
        return [
            ['name' => 'ASTM', 'image' => '#'],
            ['name' => 'CCMC', 'image' => '#'],
            ['name' => 'CGSB', 'image' => '#'],
            ['name' => 'CSA', 'image' => '#'],
            ['name' => 'FM', 'image' => '#'],
            ['name' => 'LEED', 'image' => '#'],
            ['name' => 'NABA', 'image' => '#'],
            ['name' => 'ULC', 'image' => '#'],
            ['name' => 'ABAA', 'image' => '#'],
            ['name' => 'UL', 'image' => '#'],
        ];
    }

    /**
     * @return array
     */
    private function getProductSignatures()
    {
        return [
            'Haute performance',
            'Solution sans flamme',
            'Technologie Galon Duo',
        ];
    }

    /**
     * create the base option if its needed
     *
     * @param $key
     * @param int|null $limit
     * @param bool $not_deletable
     * @return string
     */
    private function getOptionJson($key, $limit = null, $not_deletable = false)
    {
        $options = [
            'special_index_key' => $key,
        ];

        if (!is_null($limit)) {
            $options['limit'] = $limit;
        }

        if ($not_deletable) {
            $options['not_deletable'] = true;
        }

        return json_encode($options);
    }
}
