<?php

namespace Pimeo\Indexer\ModelIndexers;

use Carbon\Carbon;
use Pimeo\Events\Pim\AttributableModelIsIndexed;
use Pimeo\Events\Pim\AttributableModelIsRemovedFromIndex;
use Pimeo\Events\Pim\AttributableModelWasIndexed;
use Pimeo\Events\Pim\AttributableModelWasRemovedFromIndex;
use Pimeo\Indexer\Indexer;
use Pimeo\Models\Detail;

class DetailIndexer extends Indexer
{
    const BASE_MODEL_SUB_INDEX = 'details';

    /**
     * @param $id
     */
    public function indexOne($id)
    {
        $details = $this->getDetail($id);

        $this->indexDetail($details);
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

        event(new AttributableModelIsRemovedFromIndex($id, Detail::class, $this));

        foreach ($this->indexPerLanguageCode as $index) {
            $data = $this->client->search([
                'index' => $index,
                'type'  => self::BASE_MODEL_SUB_INDEX,
                'size'  => 10000,
                'body'  => $body,
            ]);

            if (isset($data['hits']['total']) && $data['hits']['total'] > 0) {
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
        $details = $this->getAllDetailsWithMedia();

        /** @var Detail $detail */
        foreach ($details as $detail) {
            $this->indexDetail($detail);
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
                $id = $hits['_source']['id'];
                $this->deleteOne($id);
            }
        }
    }

    protected function indexDetail(Detail $detail)
    {
        $detail_data = $this->buildOneDetailData($detail);

        event(new AttributableModelIsIndexed($detail, $this));

        foreach ($detail_data as $index => $data) {
            $this->client->index([
                'index' => $index,
                'type'  => self::BASE_MODEL_SUB_INDEX,
                'id'    => $this->attributeIndexerHelper->slugifyName($data['name'], $detail->id),
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

    private function getAllDetailsWithMedia()
    {
        return Detail::with([
            'linkAttributes',
            'linkAttributes.attribute',
        ])->where('company_id', $this->company->id)->whereHas('mediaLinks', function ($query) {
            $query->where('media_id', $this->websiteMedia->id);
        })->get();
    }

    /**
     * @param  int $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|Detail
     */
    protected function getDetail($id)
    {
        return Detail::with([
            'linkAttributes',
            'linkAttributes.attribute',
        ])->where('company_id', $this->company->id)->findOrFail($id);
    }

    protected function buildOneDetailData(Detail $detail)
    {
        $indexes = [];

        foreach ($this->indexPerLanguageCode as $langCode => $index) {
            $data = $this->getBaseModelArray();

            $data['id'] = $detail->id;

            $attribute_data = $this->attributeIndexerHelper->createAttributesByLang(
                $detail,
                $langCode,
                'detail_name',
                'never going to give you up never going to let you down'
            );

            $data['name']           = $attribute_data['name'];
            $data['name_no_spaces'] = str_replace(' ', '', $attribute_data['name']);

            /** @var Carbon $crdate */
            $crdate = $detail->updated_at;
            if (is_null($crdate)) {
                $crdate = $detail->created_at;
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

    public static function detailMapping($index)
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
                    'crdate'            => [
                        'type' => 'string',
                    ],
                    'name_first_letter' => [
                        'type'     => 'string',
                        'analyzer' => 'ascii_lower',
                    ],
                    'name_no_spaces'    => [
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
