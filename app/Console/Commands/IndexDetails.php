<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;
use Pimeo\Indexer\ModelIndexers\DetailIndexer;
use Pimeo\Models\Company;

class IndexDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:index:details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'index all details';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('index details');
        $companies = Company::all();

        foreach ($companies as $company) {
            $detailIndexer = new DetailIndexer($company);
            $detailIndexer->indexAll();
        }
    }
}
