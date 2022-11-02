<?php

namespace Pimeo\Indexer\ModelIndexers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Pimeo\Events\Pim\AttributableModelIsIndexed;
use Pimeo\Events\Pim\AttributableModelIsRemovedFromIndex;
use Pimeo\Events\Pim\AttributableModelWasIndexed;
use Pimeo\Events\Pim\AttributableModelWasRemovedFromIndex;
use Pimeo\Indexer\Indexer;
use Pimeo\Models\Layer;
use Pimeo\Models\LayerGroup;
use Pimeo\Models\System;

class SystemIndexer extends Indexer
{
    const BASE_MODEL_SUB_INDEX = 'system';

    public function indexOne($id)
    {
        $system = $this->getSystem($id);
        $this->indexSystem($system);
    }

    public function indexAll()
    {
        $systems = $this->getAllSystemWithMedia();
        foreach ($systems as $system) {
            $this->indexSystem($system);
        }
    }

    public function deleteAll()
    {
        $data = $this->client->search([
            'index' => array_values($this->indexPerLanguageCode)[0],
            'type'  => self::BASE_MODEL_SUB_INDEX,
            'size'  => 10000,
        ]);

        if (isset($data['hits']) && isset($data['hits']['total']) && $data['hits']['total'] > 0) {
            foreach ($data['hits']['hits'] as $hits) {
                $id = $hits['_source']['id'];
                $this->deleteOne($id);
            }
        }
    }

    /**
     * @param $id
     */
    public function deleteOne($id)
    {
        $body = [
            'query' => [
                'match' => [
                    'id' => $id,
                ],
            ],
        ];

        event(new AttributableModelIsRemovedFromIndex($id, System::class, $this));

        foreach ($this->indexPerLanguageCode as $index) {
            $data = $this->client->search([
                'index' => $index,
                'type'  => self::BASE_MODEL_SUB_INDEX,
                'size'  => 10000,
                'body'  => $body,
            ]);

            if (isset($data['hits']) && isset($data['hits']['total']) && $data['hits']['total'] > 0) {
                foreach ($data['hits']['hits'] as $hits) {
                    $hitsId = $hits['_id'];

                    $this->client->delete([
                        'index' => $index,
                        'type'  => self::BASE_MODEL_SUB_INDEX,
                        'id'    => $hitsId,
                    ]);
                }
            }
        }
    }

    /**
     * @return array
     */
    protected function getBaseModelArray()
    {
        return [
            'id'                => '',
            'name'              => '',
            'name_first_letter' => '',
            'attributes'        => '',
        ];
    }

    /**
     * @param System $system
     */
    private function indexSystem(System $system)
    {
        $data = $this->buildOneSystemData($system);

        event(new AttributableModelIsIndexed($system, $this));

        foreach ($data as $index => $system_data) {
            $this->client->index([
                'index' => $index,
                'type'  => self::BASE_MODEL_SUB_INDEX,
                'id'    => $this->attributeIndexerHelper->slugifyName($system_data['name'], $system->id),
                'body'  => $system_data,
            ]);
        }
    }

    private function buildOneSystemData(System $system)
    {
        $data_per_index = [];

        $originalLocale =  App::getLocale();

        foreach ($this->indexPerLanguageCode as $lang_code => $index) {
            $system_data       = $this->getBaseModelArray();
            $system_data['id'] = $system->id;

            $attribute_data = $this->attributeIndexerHelper->createAttributesByLang(
                $system,
                $lang_code,
                'system_name',
                'system_complementary_product'
            );

            if ($system->buildingComponents) {
                $values = [];
                foreach ($system->buildingComponents as $component) {
                    App::setLocale($lang_code);
                    $values[] = ['value' => trans('building-component.component.' . $component->code)];
                }

                $attribute_data['attributes'][] = [
                    'label'     => trans('building-component.index.table.header'),
                    'type'      => 'list_composante',
                    'name'      => 'list_composante',
                    'unit_type' => '',
                    'values'    => $values
                ];
            }

            $system_data['name']              = $attribute_data['name'];
            $system_data['name_no_spaces']    = str_replace(' ', '', $attribute_data['name']);
            $system_data['name_first_letter'] = mb_substr($attribute_data['name'], 0, 1);
            $system_data['date'] = $system->updated_at->format('Y-m-d H:i:s');

            if (! empty($attribute_data['attributes'])) {
                $system_data['attributes'] = $attribute_data['attributes'];
            }

            if (isset($attribute_data['complementary']) && ! empty($attribute_data['complementary'])) {
                $system_data['complementary'] = $this->attributeIndexerHelper->validateComplementaryProduct(
                    $attribute_data['complementary'],
                    $this->websiteMedia->id
                );
            }

            $system_data['layers_groups'] = $this->buildSystemLayerGroups($lang_code, $system->layerGroups);

            $data_per_index[$index] = $system_data;
        }

        App::setLocale($originalLocale);

        return $this->attributeIndexerHelper->addSlugs(
            $data_per_index,
            $this->indexPerLanguageCode,
            'name',
            'id'
        );
    }

    /**
     * @param $lang_code
     * @param Collection|LayerGroup[] $layer_groups
     *
     * @return array
     */
    private function buildSystemLayerGroups($lang_code, Collection $layer_groups)
    {
        $layer_groups_data   = [];
        $sorted_layer_groups = $layer_groups->sortBy('position');

        /** @var LayerGroup $layer_group */
        foreach ($sorted_layer_groups as $layer_group) {
            $data = [];

            $data['name']   = $layer_group->name[$lang_code];
            $data['layers'] = $this->buildSystemLayers($lang_code, $layer_group->layers);

            $layer_groups_data[] = $data;
        }

        return $layer_groups_data;
    }

    /**
     * @param $lang_code
     * @param Collection|Layer[] $layers
     *
     * @return array
     */
    public function buildSystemLayers($lang_code, Collection $layers)
    {
        $layers_data   = [];
        $sorted_layers = $layers->sortBy('position');

        /** @var Layer $layer */
        foreach ($sorted_layers as $layer) {
            $data = [];

            $data['function'] = $layer->product_function[$lang_code];
            $data['order']    = $layer->position;

            if (! is_null($layer->parent_product_id)
                && $this->attributeIndexerHelper->validateParentProduct(
                    $layer->parent_product_id,
                    $this->websiteMedia->id
                )
            ) {
                $data['product_id'] = $layer->parent_product_id;
            }

            $data['product_name'] = $layer->product_name[$lang_code];

            $layers_data[] = $data;
        }

        return $layers_data;
    }

    /**
     * @return Collection|System[]
     */
    private function getAllSystemWithMedia()
    {
        return System::with(
            'layerGroups',
            'layerGroups.layers',
            'linkAttributes',
            'linkAttributes.attribute'
        )->where('company_id', $this->company->id)->whereHas('mediaLinks', function ($query) {
            $query->where('media_id', $this->websiteMedia->id);
        })->get();
    }

    /**
     * Get the parent product with the eager loaded relations
     *
     * @param $id
     *
     * @return System
     */
    private function getSystem($id)
    {
        return System::with(
            'layerGroups',
            'layerGroups.layers',
            'linkAttributes',
            'linkAttributes.attribute',
            'mediaLinks'
        )->where('company_id', $this->company->id)->findOrFail($id);
    }

    /**
     * @param $index
     *
     * @return array
     */
    public static function systemMapping($index)
    {
        return [
            'index' => $index,
            'type'  => self::BASE_MODEL_SUB_INDEX,
            'body'  => [
                'properties' => [
                    'complementary'     => [
                        'type' => 'integer',
                    ],
                    'name'              => [
                        'type'     => 'string',
                        'analyzer' => 'ascii_lower',
                        'fields'   => [
                            'for_sort' => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                        ],
                    ],
                    'name_first_letter' => [
                        'type'     => 'string',
                        'analyzer' => 'ascii_lower',
                    ],
                    'name_no_spaces'    => [
                        'type'     => 'string',
                        'analyzer' => 'ascii_lower',
                    ],
                    'id'                => [
                        'type' => 'integer',
                    ],
                    'slugs'             => [
                        'type'       => 'nested',
                        'properties' => [
                            'code' => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                            'name' => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                        ],
                    ],
                    'date'              => [
                        'type'   => 'date',
                        'format' => 'yyyy-MM-dd HH:mm:ss',
                    ],
                    'attributes'        => [
                        'type'       => 'nested',
                        'properties' => [
                            'label'     => [
                                'type' => 'string',
                            ],
                            'type'      => [
                                'type' => 'string',
                            ],
                            'unit_type' => [
                                'type' => 'string',
                            ],
                            'values'    => [
                                'type'       => 'nested',
                                'properties' => [
                                    'date'  => [
                                        'type'  => 'string',
                                        'index' => 'not_analyzed',
                                    ],
                                    'size'  => [
                                        'type'  => 'string',
                                        'index' => 'not_analyzed',
                                    ],
                                    'type'  => [
                                        'type'  => 'string',
                                        'index' => 'not_analyzed',
                                    ],
                                    'url'   => [
                                        'type'  => 'string',
                                        'index' => 'not_analyzed',
                                    ],
                                    'value' => [
                                        'type'  => 'string',
                                        'index' => 'not_analyzed',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'layers_groups'     => [
                        'properties' => [
                            'name'   => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                            'layers' => [
                                'properties' => [
                                    'function'     => [
                                        'type'  => 'string',
                                        'index' => 'not_analyzed',
                                    ],
                                    'product_name' => [
                                        'type'  => 'string',
                                        'index' => 'not_analyzed',
                                    ],
                                    'order'        => [
                                        'type' => 'integer',
                                    ],
                                    'product_id'   => [
                                        'type' => 'integer',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
