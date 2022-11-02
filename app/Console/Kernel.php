<?php

namespace Pimeo\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Predis\Command\Command;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ChildProductImport::class,
        Commands\ImportParentProducts::class,
        Commands\ImportChildProducts::class,

        Commands\IndexProducts::class,
        Commands\DeleteProductsIndex::class,

        Commands\IndexSpecifications::class,
        Commands\DeleteSpecificationsIndex::class,

        Commands\IndexDetails::class,
        Commands\DeleteDetailsIndex::class,

        Commands\IndexDocuments::class,
        Commands\DeleteDocumentsIndex::class,

        Commands\IndexTechnicalBulletins::class,
        Commands\DeleteTechnicalBulletinsIndex::class,

        Commands\IndexAll::class,

        Commands\IndexSystem::class,
        Commands\DeleteSystemsIndex::class,

        Commands\IndexScalableAttribute::class,

        Commands\InitElasticSearchIndex::class,
        Commands\SystemImportation\RoofSystemImport::class,
        Commands\SystemImportation\WallSystemImport::class,
        Commands\SystemImportation\FoundationSystemImport::class,
        Commands\SystemImportation\BalconySystemImport::class,
        Commands\SystemImportation\PlazaDeckSystemImport::class,
        Commands\SystemImportation\BridgeSystemImport::class,
        Commands\SystemImportation\ParkingSystemImport::class,

        //Commands\HubSpotIndexer::class,

        Commands\PublishDraftsChilds::class,

        Commands\ChangeALanguageToAnother::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
    }
}
