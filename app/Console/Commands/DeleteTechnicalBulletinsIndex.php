<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;
use Pimeo\Indexer\ModelIndexers\TechnicalBulletinIndexer;
use Pimeo\Models\Company;

class DeleteTechnicalBulletinsIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:index:deleteTechnicalBulletins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete the Technical bulletins index';

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
        $this->info('index technical bulletin');
        $companies = Company::all();

        foreach ($companies as $company) {
            $technicalBulletinIndexer = new TechnicalBulletinIndexer($company);
            $technicalBulletinIndexer->deleteAll();
        }
    }
}
