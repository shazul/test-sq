<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;
use Pimeo\Indexer\ModelIndexers\DocumentIndexer;
use Pimeo\Indexer\ModelIndexers\ProductIndexer;
use Pimeo\Models\Company;

class IndexDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:index:documents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'index all documents';

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
        $this->info('Indexing documents');
        $companies = Company::all();

        foreach ($companies as $company) {
            $documentIndexer = new DocumentIndexer($company);
            $documentIndexer->indexAll();
        }
    }
}
