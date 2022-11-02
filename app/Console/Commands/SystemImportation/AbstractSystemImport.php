<?php

namespace Pimeo\Console\Commands\SystemImportation;

use InvalidArgumentException;
use Pimeo\Console\Commands\BaseImport;
use Pimeo\Console\Commands\Exceptions\InvalidParentException;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\Company;
use Pimeo\Models\LayerGroup;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\System;
use Pimeo\Repositories\ParentProductRepository;
use Symfony\Component\Console\Helper\ProgressBar;

abstract class AbstractSystemImport extends BaseImport
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
     * @var Company
     */
    private $company;

    /**
     * @var String[]
     */
    protected $layer_group_names = [];

    /**
     * @var array
     */
    protected $field_array_key_match;

    /**
     * @var array
     */
    protected $parents;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Init the system import
     */
    protected function init()
    {
        $this->company = Company::first();
        $product_repo = new ParentProductRepository();
        $this->parents = $product_repo->allForImport();
    }

    /**
     * @param array $raw_csv_data
     */
    public function start($raw_csv_data)
    {
        $filtered_csv = $this->filterRawCsv($raw_csv_data);

        $this->importSystems($filtered_csv);
    }

    /**
     * @param array $system_filtered_csv
     * @return mixed
     */
    protected function importSystems(array $system_filtered_csv)
    {
        $progress = new ProgressBar($this->getOutput(), count($system_filtered_csv));
        $progress->start();

        foreach ($system_filtered_csv as $filtered_key => $system_data) {
            $attributes = $system_data;
            //unset the keys of the groups
            foreach ($this->layer_group_names as $key => $value) {
                unset($attributes[$key]);
            }

            //unset is starred
            $is_starred = false;
            if (isset($attributes['is_starred'])) {
                $starred = $attributes['is_starred'];
                unset($attributes['is_starred']);

                if ($starred == 'Oui') {
                    $is_starred = true;
                }
            }

            //TODO add is starred !IMPORTANT! to systems
            $system = $this->createSystem($is_starred);

            foreach ($attributes as $attribute_name => $attribute_value) {
                $attribute = $this->getFieldByName($attribute_name);
                $this->createAttributeLink($system, $attribute, $attribute_value);
            }

            //build layer groups
            $groups = array_only($system_data, array_keys($this->layer_group_names));

            $group_number = 1;
            foreach ($groups as $group_key => $group) {
                if (isset($this->layer_group_names[$group_key])) {
                    $this->importGroupLayer($system, $this->layer_group_names[$group_key], $group_number, $group);
                }
                $group_number++;
            }
            $progress->advance();
        }

        $progress->clear();
        $this->clearLineUp();
    }

    /**
     * @param System $system
     * @param array $name
     * @param int $position
     * @param array $group_layers
     * @return mixed
     * @throws InvalidArgumentException
     * @throws InvalidParentException
     */
    protected function importGroupLayer(System $system, $name, $position, array $group_layers)
    {
        /** @var LayerGroup $group */
        $group = $system->layerGroups()->create([
            'position' => $position,
            'name'     => ['fr' => $name, 'en' => $name],
        ]);

        $current_position = 1;
        foreach ($group_layers as $layer) {
            try {
                $ids = $this->matchParentNameWithParentId($layer);
                $parent_id = $ids[0];
                $parent = $this->parents[$parent_id];
                if (!isset($parent['name']) && isset($parent['product_role'])) {
                    throw new InvalidArgumentException('Attributes not well assoc with the parent id:' . $parent_id);
                }
            } catch (InvalidParentException $e) {
                $parent = [
                    'name'         => ['fr' => $layer, 'en' => $layer],
                    'product_role' => ['fr' => 'ND', 'en' => 'ND'],
                ];
                $parent_id = null;
            }

            if (!isset($parent['product_role'])) {
                $product_role = ['fr' => 'ND', 'en' => 'ND'];
            } else {
                $product_role = $parent['product_role'];
            }

            $name = $parent['name'];

            $group->layers()->create([
                'parent_product_id' => $parent_id,
                'product_name'      => $name,
                'product_function'  => $product_role,
                'position'          => $current_position,
            ]);

            $current_position++;
        }
    }

    /**
     * @param array $raw_csv_data
     * @return mixed
     */
    abstract protected function filterRawCsv(array $raw_csv_data);

    /**
     * @param bool $is_starred
     * @return System
     */
    protected function createSystem($is_starred = false)
    {
        /** @var System $system */
        return factory(System::class)->create(
            [
                'is_starred'     => $is_starred,
                'company_id'     => $this->company->id,
                'status'         => AttributableModelStatus::INCOMPLETE_STATUS,
            ]
        );
    }

    /**
     * @param $data_row
     * @param $range
     * @return array
     */
    protected function buildValuesBasedOnRange($data_row, $range)
    {
        $data_in_range = [];
        foreach ($data_row as $key => $data) {
            if (in_array($key, $range)) {
                $data_in_range[] = $data;
            }
        }
        return $this->stripEmptyKeys($data_in_range);
    }

    /**
     * @param $name
     * @return Attribute
     */
    protected function getFieldByName($name)
    {
        return Attribute::where('name', $name)->where('model_type', 'system')->firstOrFail();
    }

    /**
     * @param System $system
     * @param Attribute $attribute
     * @param $raw_value
     */
    protected function createAttributeLink(System $system, Attribute $attribute, $raw_value)
    {
        $method_name = $attribute->type->specs['type'];
        if (isset($attribute->type->specs['sub_type'])) {
            $method_name = $method_name . ucfirst($attribute->type->specs['sub_type']);
        }
        $method_name = $method_name . 'BuildValue';

        $value = null;
        if (method_exists($this, $method_name)) {
            $value = $this->$method_name($attribute, $raw_value);
        } else {
            throw new \InvalidArgumentException('The method named ' . $method_name . ' does not exist');
        }

        /** @var LinkAttribute $link_attribute */
        $link_attribute = $system->linkAttributes()->create([
            'attribute_id' => $attribute->id,
        ]);

        $link_attribute->values()->create([
            'values' => $value,
        ]);
    }

    /**
     * Return the id of the value for a choice field
     *
     * @param Attribute $attribute
     * @param $values
     *
     * @return array
     */
    protected function choiceBuildValue(Attribute $attribute, $values)
    {
        $choices = $attribute->value->values['fr'];

        $id = array_search($values, $choices);
        if ($id === false) {
            throw new InvalidArgumentException(
                'The value "' . $values
                . '" was not found in the attribute ' . $attribute->name
                . ' id: ' . $attribute->id
            );
        }

        $keys = [];
        $keys[] = $id;

        return ['keys' => $keys];
    }

    /**
     * @param Attribute $attribute
     * @param $values
     * @return array
     */
    protected function choiceImageBuildValue(Attribute $attribute, $values)
    {
        return $this->baseImageChoice($attribute, $values);
    }

    /**
     * @param Attribute $attribute
     * @param $values
     * @return array
     */
    protected function choiceImageNoDisplayBuildValue(Attribute $attribute, $values)
    {
        return $this->baseImageChoice($attribute, $values);
    }

    private function baseImageChoice(Attribute $attribute, $values)
    {
        $choices = $attribute->value->values['fr'];

        //get the keys of the choices where the names match
        $names = [];
        foreach ($choices as $key => $choice) {
            if (is_array($values)) {
                //filter each values to find the name
                foreach ($values as $value) {
                    if ($choice['name'] == $value) {
                        $names[] = $key;
                    }
                }
            } else {
                if ($choice['name'] == $values) {
                    $names[] = $key;

                    //break foreach if a match is found
                    break;
                }
            }
        }

        if (empty($names)) {
            throw new InvalidArgumentException('No match found in the values of the field ' . $attribute->name);
        }

        return ['keys' => $names];
    }

    /**
     * @param Attribute $attribute
     * @param $values
     * @return array
     */
    protected function textBuildValue(Attribute $attribute, $values)
    {
        $text_value = [
            'fr' => [],
            'en' => [],
        ];

        if (is_array($values)) {
            foreach ($values as $value) {
                $text_value['fr'] = $value;
                $text_value['en'] = $value . ' (en)';
            }
        } else {
            $text_value['fr'] = $values;
            $text_value['en'] = $values . ' (en)';
        }

        return $text_value;
    }

    /**
     * @param Attribute $attribute
     * @param $values
     * @return array
     */
    protected function textUrlBuildValue(Attribute $attribute, $values)
    {
        $text_value = [
            'fr' => [],
            'en' => [],
        ];

        $text_value['fr'] = $values;
        $text_value['en'] = $values . ' (en)';

        return $text_value;
    }

    /**
     * @param Attribute $attribute
     * @param $values
     * @return string
     */
    protected function textLinkBuildValue(Attribute $attribute, $values)
    {
        $ids = [];

        if (is_array($values)) {
            foreach ($values as $value) {
                $ids[] = $this->matchParentNameWithParentId($value);
            }
        } else {
            $ids[] = $this->matchParentNameWithParentId($values);
        }

        if (empty($ids)) {
            throw new InvalidArgumentException('BAM!');
        }

        return implode(',', $ids);
    }

    /**
     * @param string $name
     * @return string
     * @throws InvalidParentException
     */
    protected function matchParentNameWithParentId($name)
    {
        $parent = array_where($this->parents, function ($key, $parent) use ($name) {
            $exist = false;
            if ($parent['name']['fr'] == $name) {
                $exist = true;
            }
            return $exist;
        });

        if (empty($parent)) {
            throw new InvalidParentException('No parent match for name ' . $name, $name);
        }

        return array_keys($parent);
    }

    /**
     * @param $group
     * @return array
     */
    protected function getProductsInGroup($group)
    {
        $data = array_where($group, function ($key, $value) {
            $is_product = false;
            if ($key % 2 == 0 && $value != '') {
                $is_product = true;
            }
            return $is_product;
        });

        return $data;
    }

    /**
     * @param $values
     * @return array
     */
    protected function stripEmptyKeys($values)
    {
        $new_values = [];
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $new_value = $this->stripEmptyKeys($value);
                if (!empty($new_values)) {
                    $new_values[$key] = $new_value;
                }
            } else {
                if ($value !== '' && $value !== null) {
                    $new_values[$key] = $value;
                }
            }
        }
        return $new_values;
    }
}
