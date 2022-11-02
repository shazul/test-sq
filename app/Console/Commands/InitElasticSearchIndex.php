<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;
use Pimeo\Indexer\CompanyIndexer;

class InitElasticSearchIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:index:initElasticSearch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'init elasticsearch indexes';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companyIndexer = new CompanyIndexer();
        $companyIndexer->indexCompanies();
    }
}
