<?php

namespace Pimeo\Indexer\ModelIndexers;

use Carbon\Carbon;
use Pimeo\Events\Pim\AttributableModelIsIndexed;
use Pimeo\Events\Pim\AttributableModelIsRemovedFromIndex;
use Pimeo\Events\Pim\AttributableModelWasIndexed;
use Pimeo\Events\Pim\AttributableModelWasRemovedFromIndex;
use Pimeo\Indexer\Indexer;
use Pimeo\Models\TechnicalBulletin;

class TechnicalBulletinIndexer extends Indexer
{
    const BASE_MODEL_SUB_INDEX = 'technical_bulletins';

    /**
     * @param $id
     */
    public function indexOne($id)
    {
        $technical_bulletin = $this->getTechnicalBulletin($id);

        $this->indexTechnicalBulletins($technical_bulletin);
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

        event(new AttributableModelIsRemovedFromIndex($id, TechnicalBulletin::class, $this));

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

    public function indexAll()
    {
        $technical_bulletins = $this->getAllTechnicalBulletinsWithMedia();

        /** @var TechnicalBulletin $technical_bulletins */
        foreach ($technical_bulletins as $technical_bulletin) {
            $this->indexTechnicalBulletins($technical_bulletin);
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

    protected function indexTechnicalBulletins(TechnicalBulletin $technical_bulletin)
    {
        $technical_bulletin_data = $this->buildOneTechnicalBulletinData($technical_bulletin);

        event(new AttributableModelIsIndexed($technical_bulletin, $this));

        foreach ($technical_bulletin_data as $index => $data) {
            $this->client->index([
                'index' => $index,
                'type'  => self::BASE_MODEL_SUB_INDEX,
                'id'    => $this->attributeIndexerHelper->slugifyName($data['name'], $technical_bulletin->id),
                'body'  => $data,
            ]);
        }
    }

    protected function getBaseModelArray()
    {
        return [
            'id'         => '',
            'name'       => '',
            'attributes' => [],
        ];
    }

    private function getAllTechnicalBulletinsWithMedia()
    {
        return TechnicalBulletin::with([
            'linkAttributes',
            'linkAttributes.attribute',
        ])->where('company_id', $this->company->id)->whereHas('mediaLinks', function ($query) {
            $query->where('media_id', $this->websiteMedia->id);
        })->get();
    }

    /**
     * @param  int $id
     *
     * @return TechnicalBulletin
     */
    protected function getTechnicalBulletin($id)
    {
        return TechnicalBulletin::with([
            'linkAttributes',
            'linkAttributes.attribute',
        ])->where('company_id', $this->company->id)->findOrFail($id);
    }

    protected function buildOneTechnicalBulletinData(TechnicalBulletin $technical_bulletin)
    {
        $indexes = [];

        foreach ($this->indexPerLanguageCode as $langCode => $index) {
            $data = $this->getBaseModelArray();

            $data['id'] = $technical_bulletin->id;

            $attribute_data = $this->attributeIndexerHelper->createAttributesByLang(
                $technical_bulletin,
                $langCode,
                'technical_bulletin_name',
                'technical_bulletin_parent_product'
            );

            $data['name']           = $attribute_data['name'];
            $data['name_no_spaces'] = str_replace(' ', '', $attribute_data['name']);

            /** @var Carbon $crdate */
            $crdate = $technical_bulletin->updated_at;
            if (is_null($crdate)) {
                $crdate = $technical_bulletin->created_at;
            }

            $data['crdate'] = $crdate->timestamp;

            $data['name_first_letter'] = mb_substr($attribute_data['name'], 0, 1);

            if (! empty($attribute_data['attributes'])) {
                $data['attributes'] = $attribute_data['attributes'];
            }

            if (isset($attribute_data['complementary']) && ! empty($attribute_data['complementary'])) {
                $data['complementary'] = $this->attributeIndexerHelper->validateComplementaryProduct(
                    $attribute_data['complementary'],
                    $this->websiteMedia->id
                );
            }

            $indexes[$index] = $data;
        }

        return $indexes;
    }

    public static function technicalBulletinMapping($index)
    {
        return [
            'index' => $index,
            'type'  => self::BASE_MODEL_SUB_INDEX,
            'body'  => [
                'properties' => [
                    'id'                => [
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
                    'name_no_spaces'    => [
                        'type'     => 'string',
                        'analyzer' => 'ascii_lower',
                    ],
                    'crdate'            => [
                        'type' => 'string',
                    ],
                    'name_first_letter' => [
                        'type'     => 'string',
                        'analyzer' => 'ascii_lower',
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
                ],
            ],
        ];
    }
}
