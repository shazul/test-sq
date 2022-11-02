<?php

namespace Tests\Libs;

use Pimeo\Indexer\CompanyIndexer;

trait DatabaseSetup
{
    protected static $migrated = false;

    protected $admin;
    protected $editor;
    protected $superAdmin;
    protected $user;

    public function setupTestDatabase()
    {
        if (! static::$migrated) {
            $this->artisan('migrate:refresh');
            $this->seed();
            // $this->app[\Illuminate\Contracts\Console\Kernel::class]->setArtisan(null);
            static::$migrated = true;
        }

        $this->setupUsers();
        $this->actingAs($this->superAdmin);

        app(CompanyIndexer::class)->deleteTestIndexes();
        app(CompanyIndexer::class)->indexCompanies();

        $this->beginDatabaseTransaction();
    }

    public function beginDatabaseTransaction()
    {
        $database = $this->app->make('db');

        foreach ($this->connectionsToTransact() as $name) {
            $database->connection($name)->beginTransaction();
        }

        $this->beforeApplicationDestroyed(function () use ($database) {
            foreach ($this->connectionsToTransact() as $name) {
                $database->connection($name)->rollBack();
            }
        });
    }

    protected function connectionsToTransact()
    {
        return property_exists($this, 'connectionsToTransact')
                            ? $this->connectionsToTransact : [null];
    }

    public function setupUsers()
    {
        $this->superAdmin = $this->createSuperAdminUser();
        $this->admin = $this->createAdminUser();
        $this->editor = $this->createEditor();
        $this->user = $this->createUser();
    }
}
