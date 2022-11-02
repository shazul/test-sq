<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;
use Pimeo\Indexer\ModelIndexers\DetailIndexer;
use Pimeo\Models\Company;

class DeleteDetailsIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:index:deleteDetails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete the detail index';

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
        $this->info('index details');
        $companies = Company::all();

        foreach ($companies as $company) {
            $detailIndexer = new DetailIndexer($company);
            $detailIndexer->deleteAll();
        }
    }
}
