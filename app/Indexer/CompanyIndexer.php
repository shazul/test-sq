<?php

namespace Pimeo\Indexer;

use Elasticsearch\ClientBuilder;
use Exception;
use InvalidArgumentException;
use Pimeo\Indexer\ModelIndexers\AttributeScalableIndexer;
use Pimeo\Indexer\ModelIndexers\DetailIndexer;
use Pimeo\Indexer\ModelIndexers\DocumentIndexer;
use Pimeo\Indexer\ModelIndexers\ProductIndexer;
use Pimeo\Indexer\ModelIndexers\SpecificationIndexer;
use Pimeo\Indexer\ModelIndexers\SystemIndexer;
use Pimeo\Indexer\ModelIndexers\TechnicalBulletinIndexer;
use Pimeo\Models\Company;
use Pimeo\Models\Language;
use Pimeo\Models\Media;

class CompanyIndexer
{

    /** @var Company[] */
    private $companies;

    private $websiteMediaPerCompaniesId;

    private $indexPerLanguagesAndCompaniesId;

    /** @var \Elasticsearch\Client */
    public $client;

    /**
     * CompanyIndexer constructor.
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([config('elasticsearch.client_ip') . ':' . config('elasticsearch.client_port')])
            ->setRetries(0)
            ->build();

        $this->companies = Company::all();
        $this->getCompanyWebsiteMedia();
        $this->getCompaniesIndexesByLanguage();
    }

    /**
     * Delete all indexes
     */
    public function deleteIndexes()
    {
        $this->client->indices()->delete(['index' => '*']);
    }

    /**
     * Delete all test indexes
     */
    public function deleteTestIndexes()
    {
        $this->client->indices()->delete(['index' => 'test-*']);
    }

    /**
     * Delete all company indexes
     */
    public function deleteCompanyIndexes()
    {
        foreach ($this->companies as $company) {
            $this->deleteIndex($company);
        }
    }

    /**
     * delete one company index
     *
     * @param Company $company
     */
    public function deleteIndex(Company $company)
    {
        $indexes = $this->getIndexesByCompanyId($company->id);
        foreach ($indexes as $index) {
            try {
                $this->client->indices()->delete(['index' => $index]);
            } catch (Exception $e) {
                logger('An exception occured on index:' . $index . ' message:' . $e->getMessage());
            }
        }
    }

    public function indexCompanies()
    {
        $this->deleteCompanyIndexes();

        foreach ($this->companies as $company) {
            $this->indexCompany($company);
        }
    }

    public function indexCompany(Company $company)
    {
        $indexes = $this->getIndexesByCompanyId($company->id);
        foreach ($indexes as $index) {
            $param = $this->baseIndexConfiguration();
            $param['index'] = $index;

            if ($this->client->indices()->exists(['index' => $index])) {
                $this->client->indices()->delete(['index' => $index]);
            }

            $this->client->indices()->create($param);
            $this->client->indices()->putMapping(ProductIndexer::productMapping($index));
            $this->client->indices()->putMapping(AttributeScalableIndexer::attributeMapping($index));
            $this->client->indices()->putMapping(SystemIndexer::systemMapping($index));
            $this->client->indices()->putMapping(SpecificationIndexer::specificationMapping($index));
            $this->client->indices()->putMapping(DetailIndexer::detailMapping($index));
            $this->client->indices()->putMapping(TechnicalBulletinIndexer::technicalBulletinMapping($index));
            $this->client->indices()->putMapping(DocumentIndexer::documentMapping($index));
        }
    }

    /**
     * @return array
     */
    public function getIndexesByCompanies()
    {
        return $this->indexPerLanguagesAndCompaniesId;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Company[]
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * @param $companyId
     *
     * @return Media
     */
    public function getWebsiteMediaByCompanyId($companyId)
    {
        return $this->websiteMediaPerCompaniesId[$companyId];
    }

    /**
     * @param $companyId
     *
     * @return mixed
     */
    public function getIndexesByCompanyId($companyId)
    {
        return $this->indexPerLanguagesAndCompaniesId[$companyId];
    }

    /**
     * Get the company website media if it exist and throw an exception if the company has no website media.
     */
    private function getCompanyWebsiteMedia()
    {
        $websiteMedia = Media::where('code', Media::CODE_WEBSITE)->first();

        foreach ($this->companies as $company) {
            $media = $company->medias()->wherePivot('media_id', $websiteMedia->id)->first();

            if (is_null($media)) {
                throw new InvalidArgumentException('No website media found for this company');
            }

            $this->websiteMediaPerCompaniesId[$company->id] = $media;
        }
    }

    private function getCompaniesIndexesByLanguage()
    {
        foreach ($this->companies as $company) {
            $this->indexPerLanguagesAndCompaniesId[$company->id] = $this->getCompanyIndexesNameByLanguage($company);
        }
    }

    /**
     * Get all index names for each language of a company
     *
     * @param Company $company
     *
     * @return array
     */
    private function getCompanyIndexesNameByLanguage(Company $company)
    {
        $indexesPerLanguages = [];

        /** @var Language[] $languages */
        $languages = $company->languages;

        foreach ($languages as $language) {
            $index = 'company-' . $company->id . '-' . $language->code;
            if (app()->environment('testing')) {
                $index = 'test-' . $index;
            }
            $indexesPerLanguages[$language->code] = $index;
        }

        return $indexesPerLanguages;
    }

    /**
     * @return array
     */
    private function baseIndexConfiguration()
    {
        return [
            'body' => [
                'settings' => [
                    'analysis' => [
                        'analyzer' => [
                            'ascii_lower' => [
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'filter' => ['lowercase', 'asciifolding'],
                            ],
                            'autocomplete' => [
                                'type' => 'custom',
                                'tokenizer' => 'autocomplete_tokenizer',
                                'filter' => ['lowercase', 'asciifolding', 'autocomplete_filter'],
                            ],
                            'autocomplete_search' => [
                                'type' => 'custom',
                                'tokenizer' => 'autocomplete_tokenizer',
                                'filter' => ['lowercase', 'asciifolding'],
                            ],
                        ],
                        'filter' => [
                            'autocomplete_filter' => [
                                'type' => 'edge_ngram',
                                'min_gram' => 1,
                                'max_gram' => 16,
                                'side' => 'front',
                            ],
                        ],
                        'tokenizer' => [
                            'autocomplete_tokenizer' => [
                                'type' => 'whitespace',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
