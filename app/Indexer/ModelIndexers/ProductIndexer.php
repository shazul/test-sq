<?php

namespace Pimeo\Indexer\ModelIndexers;

use Pimeo\Events\Pim\AttributableModelIsIndexed;
use Pimeo\Events\Pim\AttributableModelIsRemovedFromIndex;
use Pimeo\Events\Pim\AttributableModelWasIndexed;
use Pimeo\Events\Pim\AttributableModelWasRemovedFromIndex;
use Pimeo\Indexer\Indexer;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\ParentProduct;

class ProductIndexer extends Indexer
{
    const BASE_MODEL_SUB_INDEX = 'product';

    /**
     * Index one parent product
     *
     * @param int $id the id of a parent
     */
    public function indexOne($id)
    {
        $parent = $this->getParentProduct($id);
        $this->indexParent($parent);
    }

    /**
     * Delete one parent product
     *
     * @param int $id delete one parent product
     */
    public function deleteOne($id)
    {
        $body = [
            'query' => [
                'match' => [
                    'parent_id' => $id,
                ],
            ],
        ];

        event(new AttributableModelIsRemovedFromIndex($id, ParentProduct::class, $this));

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
     * Index all parents
     */
    public function indexAll()
    {
        $parents = $this->getAllParentWithMedia();

        /** @var ParentProduct $parent */
        foreach ($parents as $parent) {
            $this->indexParent($parent);
        }
    }

    /**
     * delete all parents
     */
    public function deleteAll()
    {
        $data = $this->client->search([
            'index' => array_values($this->indexPerLanguageCode)[0],
            'type'  => self::BASE_MODEL_SUB_INDEX,
            'size'  => 10000,
        ]);

        if (isset($data['hits']) && isset($data['hits']['total']) && $data['hits']['total'] > 0) {
            foreach ($data['hits']['hits'] as $hits) {
                $id = $hits['_source']['parent_id'];
                $this->deleteOne($id);
            }
        }
    }

    /**
     *
     * @param ParentProduct $parent
     */
    protected function indexParent($parent)
    {
        if ($this->validateParent($parent)) {
            $data = $this->buildOneParentProductData($parent);

            event(new AttributableModelIsIndexed($parent, $this));

            foreach ($data as $index => $parent_data) {
                $this->client->index([
                    'index' => $index,
                    'type'  => self::BASE_MODEL_SUB_INDEX,
                    'id'    => $this->attributeIndexerHelper->slugifyName($parent_data['name'], $parent->id),
                    'body'  => $parent_data,
                ]);
            }
        }
    }

    /**
     * Return the base model for a product
     *
     * @return array
     */
    protected function getBaseModelArray()
    {
        return [
            'is_parent'  => '',
            'name'       => '',
            'attributes' => [],
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    private function getAllParentWithMedia()
    {
        return ParentProduct::with(
            'childProducts',
            'linkAttributes',
            'linkAttributes.attribute',
            'childProducts.linkAttributes',
            'childProducts.linkAttributes.attribute',
            'childProducts.linkAttributes.attribute.type',
            'childProducts.linkAttributes.attribute.label'
        )->where('company_id', $this->company->id)->whereHas('mediaLinks', function ($query) {
            $query->where('media_id', $this->websiteMedia->id);
        })->get();
    }

    /**
     * Get the parent product with the eager loaded relations
     *
     * @param $id
     *
     * @return ParentProduct
     */
    private function getParentProduct($id)
    {
        return ParentProduct::with(
            'childProducts',
            'linkAttributes',
            'linkAttributes.attribute',
            'childProducts.linkAttributes',
            'childProducts.linkAttributes.attribute',
            'childProducts.linkAttributes.attribute.type',
            'childProducts.linkAttributes.attribute.label'
        )->where('company_id', $this->company->id)->findOrFail($id);
    }

    /**
     * @param ParentProduct $parent
     *
     * @return array
     */
    private function buildOneParentProductData(ParentProduct $parent)
    {
        $data_per_index = [];

        //loop on indexes to create the base data to send
        foreach ($this->indexPerLanguageCode as $lang_code => $index) {
            $parent_data              = $this->getBaseModelArray();
            $parent_data['parent_id'] = $parent->id;
            $parent_data['is_parent'] = 1;

            $attribute_data = $this->attributeIndexerHelper->createAttributesByLang(
                $parent,
                $lang_code,
                'name',
                'complementary_product'
            );

            $parent_data['name_no_spaces']    = str_replace(' ', '', $attribute_data['name']);
            $parent_data['name_first_letter'] = mb_substr($attribute_data['name'], 0, 1);
            $parent_data['new_product']       = $parent['new_product'];
            $parent_data['star_product']      = $parent['star_product'];
            $parent_data['date']              = $parent->updated_at->format('Y-m-d H:i:s');

            if (!empty($attribute_data['attributes'])) {
                $parent_data['attributes'] = $attribute_data['attributes'];
            }

            if (isset($attribute_data['complementary']) && !empty($attribute_data['complementary'])) {
                $parent_data['complementary'] = $this->attributeIndexerHelper->validateComplementaryProduct(
                    $attribute_data['complementary'],
                    $this->websiteMedia->id
                );
            }

            $parent_data['children'] = $this->buildChildrenAttributesByLanguage(
                $lang_code,
                $parent->id,
                $parent->childProducts
            );

            $parent_data = array_merge($parent_data, $attribute_data);

            $data_per_index[$index] = $parent_data;
        }

        return $this->attributeIndexerHelper->addSlugs(
            $data_per_index,
            $this->indexPerLanguageCode,
            'name',
            'parent_id'
        );
    }

    /**
     * @param string $language_code
     * @param int $parent_id
     * @param ChildProduct[] $children
     *
     * @return array
     */
    private function buildChildrenAttributesByLanguage($language_code, $parent_id, $children)
    {
        $children_data = [];

        foreach ($children as $child) {
            if ($child->status == AttributableModelStatus::PUBLISHED_STATUS && !$child->mediaLinks->isEmpty()) {
                $child_data = $this->getBaseModelArray();

                $child_data['product_id'] = $child->id;
                $child_data['parent_id']  = $parent_id;
                $child_data['is_parent']  = false;

                $attribute_data = $this->attributeIndexerHelper->createAttributesByLang(
                    $child,
                    $language_code,
                    'child_product_name',
                    'complementary_child_product'
                );

                $child_data['name']       = $attribute_data['name'];
                $child_data['attributes'] = [];

                if (isset($attribute_data['attributes'])) {
                    $child_data['attributes'] = $attribute_data['attributes'];
                }

                $children_data[] = $child_data;
            }
        }

        return $children_data;
    }

    private function validateParent(ParentProduct $parent)
    {
        //validate parent
        $parent_is_published = false;

        if ($parent->status == AttributableModelStatus::PUBLISHED_STATUS && $parent->isPublishable()) {
            $parent_is_published = true;
        }

        return $parent_is_published;
    }

    /**
     * Get the product index mapping
     *
     * @param $index
     *
     * @return array
     */
    public static function productMapping($index)
    {
        return [
            'index' => $index,
            'type'  => self::BASE_MODEL_SUB_INDEX,
            'body'  => [
                'properties' => [
                    'complementary'     => [
                        'type' => 'integer',
                    ],
                    'is_parent'         => [
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
                            'suggest' => [
                                'type' => 'string',
                                'index_analyzer' => 'autocomplete',
                                'search_analyzer' => 'autocomplete_search',
                            ],
                        ],
                    ],
                    'search_keywords'   => [
                        'type'                => 'string',
                        'analyzer'            => 'ascii_lower',
                        'position_offset_gap' => 100,
                        'fields'              => [
                            'raw' => [
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
                    'star_product'      => [
                        'type' => 'boolean',
                    ],
                    'new_product'       => [
                        'type' => 'boolean',
                    ],
                    'parent_id'         => [
                        'type'  => 'integer',
                        'index' => 'not_analyzed',
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
                                        'type'   => 'string',
                                        'index'  => 'not_analyzed',
                                        'fields' => [
                                            'analyzed_value' => [
                                                'type'     => 'string',
                                                'analyzer' => 'ascii_lower',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'children'          => [
                        'properties' => [
                            'name'              => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                            'name_first_letter' => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                            'parent_id'         => [
                                'type'  => 'integer',
                                'index' => 'not_analyzed',
                            ],
                            'product_id'        => [
                                'type'  => 'integer',
                                'index' => 'not_analyzed',
                            ],
                            'attributes'        => [
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
                        ],
                    ],
                ],
            ],
        ];
    }
}
