<?php

namespace Pimeo\Indexer\ModelIndexers;

use Pimeo\Indexer\Indexer;
use Pimeo\Models\Attribute;

class AttributeScalableIndexer extends Indexer
{
    const BASE_MODEL_SUB_INDEX = 'attribute';

    public function indexOne($id)
    {
        $attribute = $this->getAttributeById($id);
        $data = $this->prepareAttributeDataPerIndex($attribute);
        $this->indexOneAttribute($data);
    }

    public function deleteOne($id)
    {
        foreach ($this->indexPerLanguageCode as $index) {
            $this->client->delete([
                'index' => $index,
                'type'  => self::BASE_MODEL_SUB_INDEX,
                'id'    => $id,
            ]);
        }
    }

    public function indexAll()
    {
        $attributes = $this->getIndaxableAttributesByCompany($this->company->id);

        foreach ($attributes as $attribute) {
            $data = $this->prepareAttributeDataPerIndex($attribute);
            $this->indexOneAttribute($data);
        }
    }

    public function deleteAll()
    {
        $data = $this->client->search([
            'index' => array_values($this->indexPerLanguageCode)[0],
            'type'  => self::BASE_MODEL_SUB_INDEX,
            'size'  => 10000,
        ]);

        if (isset($data['hits']['total']) && $data['hits']['total'] > 0) {
            foreach ($data['hits']['hits'] as $hits) {
                $id = $hits['_id'];
                $this->deleteOne($id);
            }
        }
    }

    private function indexOneAttribute(array $attribute_data)
    {
        foreach ($attribute_data as $index => $data) {
            $this->client->index([
                'index' => $index,
                'type'  => self::BASE_MODEL_SUB_INDEX,
                'id'    => $data['id'],
                'body'  => $data,
            ]);
        }
    }

    private function prepareAttributeDataPerIndex(Attribute $attribute)
    {
        $data_per_index = [];
        foreach ($this->indexPerLanguageCode as $language => $index) {
            $data = $this->getBaseModelArray();
            $data['id'] = $attribute->id;

            $name = $attribute->name;
            if (isset($attribute->options['special_index_key'])) {
                $name = $attribute->options['special_index_key'];
            }
            $data['name'] = $name;
            $data['label'] = $attribute->label->values[$language];
            $data['model'] = $attribute->getModelType();

            if ($attribute->has_value) {
                $values = $attribute->value->values[$language];
                $type = $attribute->type->specs['type'];
                $has_subtype = false;
                if (isset($attribute->type->specs['sub_type'])) {
                    $has_subtype = true;
                }

                if ($type == 'choice') {
                    if ($has_subtype) {
                        $data['values'] = $this->getNameImageFromValues($values);
                    } else {
                        $data['values'] = $this->getNameFromValues($values);
                    }

                    $data['values'] = array_values($data['values']);

                    $data_per_index[$index] = $data;
                }
            }
        }

        return $data_per_index;
    }

    private function getNameImageFromValues($values)
    {
        return collect($values)->map(function ($item) {
            return [
                'name'  => $item['name'],
                'image' => getenv('FILES_ADDRESS') . '/' . $item['image'],
            ];
        })->toArray();
    }

    private function getNameFromValues($values)
    {
        return collect($values)->map(function ($item) {
            return ['name' => $item];
        })->toArray();
    }

    /**
     * @param $id
     * @return Attribute
     */
    private function getAttributeById($id)
    {
        return Attribute::with('value', 'type', 'label')->where('company_id', $this->company->id)->findOrFail($id);
    }

    /**
     * @param int $company_id
     */
    private function getIndaxableAttributesByCompany($company_id)
    {
        return Attribute::where('company_id', $company_id)
            ->where('should_index', 1)
            ->with(
                'value',
                'type',
                'label'
            )
            ->get();
    }

    protected function getBaseModelArray()
    {
        return [
            'id'     => '',
            'name'   => '',
            'model'  => '',
            'label'  => '',
            'values' => [],
        ];
    }

    /**
     * components
     *
     * @param $index
     * @return array
     */
    public static function attributeMapping($index)
    {
        return [
            'index' => $index,
            'type'  => self::BASE_MODEL_SUB_INDEX,
            'body'  => [
                'properties' => [
                    'name'   => [
                        'type'  => 'string',
                        'index' => 'not_analyzed',
                    ],
                    'id'     => [
                        'type'  => 'string',
                        'index' => 'not_analyzed',
                    ],
                    'model'  => [
                        'type'  => 'string',
                        'index' => 'not_analyzed',
                    ],
                    'label'  => [
                        'type'  => 'string',
                        'index' => 'not_analyzed',
                    ],
                    'values' => [
                        'type'       => 'nested',
                        'properties' => [
                            'name'  => [
                                'type'                => 'string',
                                'position_offset_gap' => 100,
                                'fields'              => [
                                    'suggest' => [
                                        'type'            => 'string',
                                        'index_analyzer'  => 'autocomplete',
                                        'search_analyzer' => 'autocomplete_search',
                                    ],
                                ],
                            ],
                            'image' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
