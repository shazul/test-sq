<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;
use Pimeo\Indexer\ModelIndexers\ProductIndexer;
use Pimeo\Models\Company;

class IndexProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:index:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'index all products';

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
        $this->info('index products');
        $companies = Company::all();

        foreach ($companies as $company) {
            $productIndexer = new ProductIndexer($company);
            $productIndexer->indexAll();
        }
    }
}
