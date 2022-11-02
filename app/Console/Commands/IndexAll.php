<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class IndexAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:index:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'index all';

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
        $this->info('Indexing everything');
        $this->info('...');

        $this->info('Init ElasticSearch Indexes');
        Artisan::call('soprema:index:initElasticSearch');

        $this->info('Index scalableAttributes');
        Artisan::call('soprema:index:scalableAttributes');

        $this->info('Index products');
        Artisan::call('soprema:index:products');

        $this->info('Index systems');
        Artisan::call('soprema:index:systems');

        $this->info('Index details');
        Artisan::call('soprema:index:details');

        $this->info('Index specifications');
        Artisan::call('soprema:index:specifications');

        $this->info('Index technicalBulletins');
        Artisan::call('soprema:index:technicalBulletins');

        $this->info('...');
        $this->info('Full indexation done, thank you!');
    }
}
