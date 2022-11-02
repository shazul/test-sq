<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;
use Pimeo\Indexer\ModelIndexers\AttributeScalableIndexer;
use Pimeo\Models\Company;

class IndexScalableAttribute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:index:scalableAttributes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'index all attributes';

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
        $this->info('index attributes');
        $companies = Company::all();

        foreach ($companies as $company) {
            $attributeIndexer = new AttributeScalableIndexer($company);
            $attributeIndexer->indexAll();
        }
    }
}
