<?php

namespace Pimeo\Indexer\ModelIndexers;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Pimeo\Indexer\Indexer;
use Pimeo\Models\Attributable;
use Pimeo\Models\Attribute;
use Pimeo\Models\Company;
use Pimeo\Models\Detail;
use Pimeo\Models\LinkAttribute;

class DocumentIndexer extends Indexer
{
    const BASE_MODEL_SUB_INDEX = 'document';

    private $keywordSearchAttribute;

    /**
     * DocumentIndexer constructor.
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        parent::__construct($company);
        $this->keywordSearchAttribute = $this->getSearchKeywordsAttribute();
    }


    public function indexOne($id)
    {
        $document = $this->getDocument($id);
        $this->indexDocument($document);
    }

    public function deleteOne($id)
    {
        $body = [
            'query' => [
                'match' => [
                    'link_attribute_id' => $id,
                ],
            ],
        ];

        foreach ($this->indexPerLanguageCode as $index) {
            try {
                $data = $this->client->search([
                    'index' => $index,
                    'type'  => self::BASE_MODEL_SUB_INDEX,
                    'size'  => 10000,
                    'body'  => $body,
                ]);

                if (isset($data['hits']) && isset($data['hits']['total']) && $data['hits']['total'] > 0) {
                    foreach ($data['hits']['hits'] as $hits) {
                        $id = $hits['_id'];

                        $this->client->delete([
                            'index' => $index,
                            'type'  => self::BASE_MODEL_SUB_INDEX,
                            'id'    => $id,
                        ]);
                    }
                }
            } catch (Missing404Exception $e) {
                //if 404 ignore
            }
        }
    }

    public function indexAll()
    {
        $documents = $this->getAllDocument();

        foreach ($documents as $document) {
            $this->indexDocument($document);
        }
    }

    public function indexAllFromAttributableModel(Attributable $model)
    {
        $documents = $this->getAllDocumentFromAttributableModel($model);

        foreach ($documents as $document) {
            $this->indexDocument($document);
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
                $id = $hits['_source']['link_attribute_id'];
                $this->deleteOne($id);
            }
        }
    }

    public function deleteAllFromAttributableModel($id, $class)
    {
        $data = $this->client->search([
            'index' => array_values($this->indexPerLanguageCode)[0],
            'type'  => self::BASE_MODEL_SUB_INDEX,
            'size'  => 10000,
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'match' => [
                                    'attributable_id' => $id,
                                ],
                            ],
                            [
                                'match' => [
                                    'attributable_type' => $class,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        if (isset($data['hits']) && isset($data['hits']['total']) && $data['hits']['total'] > 0) {
            foreach ($data['hits']['hits'] as $hits) {
                $id = $hits['_source']['link_attribute_id'];
                $this->deleteOne($id);
            }
        }
    }

    protected function getBaseModelArray()
    {
        return [
            'label' => '',
            'type'  => '',
            'files' => [],
        ];
    }

    private function getDocument($id)
    {
        return LinkAttribute::findOrFail($id);
    }

    private function getAllDocument()
    {
        return LinkAttribute::join('attributes', 'attributes.id', '=', 'link_attributes.attribute_id')
            ->join('attribute_types', 'attribute_types.id', '=', 'attributes.attribute_type_id')
            ->whereIn('attribute_types.code', ['file', 'files'])
            ->where('attributes.company_id', $this->company->id)
            ->get(['link_attributes.*']);
    }

    /**
     * @param $model Attributable Attributable model to load all linked file and files attributes.
     *
     * @return mixed
     */
    public function getAllDocumentFromAttributableModel(Attributable $model)
    {
        return LinkAttribute::join('attributes', 'attributes.id', '=', 'link_attributes.attribute_id')
            ->join('attribute_types', 'attribute_types.id', '=', 'attributes.attribute_type_id')
            ->whereIn('attribute_types.code', ['file', 'files'])
            ->where('link_attributes.attributable_id', $model->id)
            ->where('link_attributes.attributable_type', get_class($model))
            ->where('attributes.company_id', $this->company->id)
            ->get(['link_attributes.*']);
    }

    protected function indexDocument($document)
    {
        if ($document->attributable->isIndexable()) {
            $data = $this->buildOneDocumentData($document);
            foreach ($data as $index => $document_data) {
                $this->client->index([
                    'index' => $index,
                    'type'  => self::BASE_MODEL_SUB_INDEX,
                    'id'    => $this->attributeIndexerHelper->slugifyName(
                        $document_data['type'],
                        $document->id
                    ),
                    'body'  => $document_data,
                ]);
            }
        }
    }

    private function buildOneDocumentData(LinkAttribute $linkAttribute)
    {
        $data_per_index = [];

        foreach ($this->indexPerLanguageCode as $lang_code => $index) {
            $files = $linkAttribute->values;
            $type = get_class($linkAttribute->attributable);

            $document_data = $this->getBaseModelArray();
            $document_data['label'] = $linkAttribute->attribute->label->getValue($lang_code);
            $document_data['type'] = $linkAttribute->attribute->name;
            $document_data['link_attribute_id'] = $linkAttribute->id;
            $document_data['attribute_id'] = $linkAttribute->attribute_id;
            $document_data['attributable_id'] = $linkAttribute->attributable_id;
            $document_data['attributable_type'] = $type;
            $document_data['attributable_name'] = $linkAttribute->attributable->getName($lang_code);
            $document_data['files'] = [];
            $document_data['building_components'] = [];
            $document_data['list_function'] = [];

            $buildingComponents = $this->getLinkAttributableBuildingComponent($linkAttribute, $lang_code);
            if (!is_null($buildingComponents)) {
                foreach ($buildingComponents as $component) {
                    $document_data['building_components'][] = [
                        'name' => $component,
                    ];
                }
            }

            $functions = $this->getLinkAttributableFunction($linkAttribute, $lang_code);
            if (!is_null($functions)) {
                foreach ($functions as $function) {
                    $document_data['list_function'][] = [
                        'name' => $function['name'],
                    ];
                }
            }

            if ($type == 'Pimeo\Models\Detail') {
                /** @var Detail $detail */
                $detail = $linkAttribute->attributable;
                $searchKeywordLinkAttribute = $this->getSearchKeywordsLinkAttribute($detail);
                if (!is_null($searchKeywordLinkAttribute)) {
                    $linkAttributeValues = $searchKeywordLinkAttribute->values;
                    $values = !empty($linkAttributeValues) ? $linkAttributeValues['keys'] : [];
                    $document_data['search_keywords'] = isset($values[$lang_code]) ? $values[$lang_code] : [];
                }
            }

            if (!empty($files)) {
                foreach ($files[$lang_code] as $key => $file) {
                    if (isset($file['empty_file'])) {
                        continue;
                    }

                    $document_data['files'][] = [
                        'name'      => $file['name'],
                        'size'      => $this->attributeIndexerHelper->bytesToSize(
                            $lang_code,
                            $file['file_size']
                        ),
                        'url'       => getenv('FILES_ADDRESS') . '/' . $file['file_path'],
                        'extension' => $file['extension'],
                    ];
                }
            }

            if (!empty($document_data['files'])) {
                $data_per_index[$index] = $document_data;
            }
        }

        return $data_per_index;
    }

    /**
     * @param Detail $detail
     *
     * @return LinkAttribute|null
     */
    private function getSearchKeywordsLinkAttribute(Detail $detail)
    {
        /** @var LinkAttribute $linkAttribute */
        $linkAttribute = LinkAttribute::whereAttributeId($this->keywordSearchAttribute->id)
            ->where('attributable_id', $detail->id)
            ->first();

        return $linkAttribute;
    }

    /**
     * @return null|Attribute
     */
    private function getSearchKeywordsAttribute()
    {
        $companyId = $this->company->id;

        /** @var Attribute $attribute */
        $attribute = Attribute::whereName('search_keywords')
            ->where('company_id', $companyId)
            ->where('model_type', 'detail')
            ->first();

        return $attribute;
    }

    public function getLinkAttributableBuildingComponent(LinkAttribute $linkAttribute, $languageCode)
    {
        /** @var Attributable $attributable */
        $attributable = $linkAttribute->attributable;

        return $attributable->getBuildingComponents($languageCode, $attributable->company_id);
    }

    public function getLinkAttributableFunction(LinkAttribute $linkAttribute, $languageCode)
    {
        /** @var Attributable $attributable */
        $attributable = $linkAttribute->attributable;

        return $attributable->getFunction($languageCode, $attributable->company_id);
    }

    public static function documentMapping($index)
    {
        return [
            'index' => $index,
            'type'  => self::BASE_MODEL_SUB_INDEX,
            'body'  => [
                'properties' => [
                    'label'               => [
                        'type'     => 'string',
                        'analyzer' => 'ascii_lower',
                        'fields'   => [
                            'for_aggs' => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                        ],
                    ],
                    'type'                => [
                        'type'     => 'string',
                        'analyzer' => 'ascii_lower',
                    ],
                    'files'               => [
                        'type'       => 'nested',
                        'properties' => [
                            'name'      => [
                                'type'     => 'string',
                                'analyzer' => 'ascii_lower',
                            ],
                            'size'      => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                            'url'       => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                            'extension' => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                        ],
                    ],
                    'building_components' => [
                        'type'       => 'nested',
                        'properties' => [
                            'name' => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                        ],
                    ],
                    'list_function'       => [
                        'type'       => 'nested',
                        'properties' => [
                            'name' => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
                            ],
                        ],
                    ],
                    'link_attribute_id'   => [
                        'type'  => 'integer',
                        'index' => 'not_analyzed',
                    ],
                    'attribute_id'        => [
                        'type'  => 'integer',
                        'index' => 'not_analyzed',
                    ],
                    'attributable_id'     => [
                        'type'  => 'integer',
                        'index' => 'not_analyzed',
                    ],
                    'attributable_type' => [
                        'type'  => 'string',
                        'index' => 'not_analyzed',
                    ],
                    'attributable_name' => [
                        'type'     => 'string',
                        'analyzer' => 'ascii_lower',
                        'fields'   => [
                            'for_sort' => [
                                'type'  => 'string',
                                'index' => 'not_analyzed',
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
                ],
            ],
        ];
    }
}
