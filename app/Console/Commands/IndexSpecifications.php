<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;
use Pimeo\Indexer\ModelIndexers\SpecificationIndexer;
use Pimeo\Models\Company;

class IndexSpecifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:index:specifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'index all specifications';

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
        $this->info('index specifications');
        $companies = Company::all();

        foreach ($companies as $company) {
            $specificationIndexer = new SpecificationIndexer($company);
            $specificationIndexer->indexAll();
        }
    }
}
