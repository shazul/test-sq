<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;
use Pimeo\Indexer\ModelIndexers\SystemIndexer;
use Pimeo\Models\Company;

class DeleteSystemsIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:index:deleteSystems';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete the system index';

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
        $this->info('index Systems');
        $companies = Company::all();

        foreach ($companies as $company) {
            $systemIndexer = new SystemIndexer($company);
            $systemIndexer->deleteAll();
        }
    }
}
