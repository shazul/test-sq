<?php

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Models\User;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class RememberListingArgumentsTest extends TestCase
{
    use DatabaseSetup;

    protected $baseUrl = 'http://pim.soprema.local';

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();
        $this->actingAs($this->user);
    }

    public function testRemembersSearch()
    {
        $this->visit('parent-product/listing')
             ->type('Sheet', 'search')
             ->press('Search')
             ->seeInField('search', 'Sheet')
             ->see('Sheet')
             ->dontSee('Liquid')
        ;

        $this->visit('parent-product/listing/draft')
             ->seeInField('search', 'Sheet')
             ->see('Sheet')
             ->dontSee('Liquid')
        ;
    }
}
