<?php

namespace Pimeo\Indexer;

use Elasticsearch\Client;
use Pimeo\Indexer\ModelIndexers\AttributeIndexerHelper;
use Pimeo\Models\Company;
use Pimeo\Models\Media;

abstract class Indexer
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Company
     */
    protected $company;

    /**
     * @var Media
     */
    protected $websiteMedia;

    /**
     * @var string[]
     */
    protected $indexPerLanguageCode;

    /**
     * @var string
     */
    protected $baseIndexName;

    /**
     * @var AttributeIndexerHelper
     */
    protected $attributeIndexerHelper;

    /**
     * build the client
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $client = new CompanyIndexer();
        $companyId = $company->id;

        $this->client = $client->client;
        $this->company = $company;

        $this->baseIndexName = $companyId;
        $this->websiteMedia = $client->getWebsiteMediaByCompanyId($companyId);
        $this->indexPerLanguageCode = $client->getIndexesByCompanyId($companyId);

        $this->attributeIndexerHelper = new AttributeIndexerHelper($company->languages);
    }

    abstract public function indexOne($id);

    abstract public function deleteOne($id);

    abstract public function indexAll();

    abstract public function deleteAll();

    abstract protected function getBaseModelArray();

    public function getCompany()
    {
        return $this->company;
    }
}
