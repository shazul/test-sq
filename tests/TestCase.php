<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Tests\Libs\CreatesAttribute;
use Tests\Libs\CreatesChildProduct;
use Tests\Libs\CreatesCompany;
use Tests\Libs\CreatesDetail;
use Tests\Libs\CreatesParentProduct;
use Tests\Libs\CreatesSpecification;
use Tests\Libs\CreatesSystem;
use Tests\Libs\CreatesTechnicalBulletin;
use Tests\Libs\CreatesUser;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesAttribute, CreatesChildProduct, CreatesCompany, CreatesDetail;
    use CreatesParentProduct, CreatesSpecification, CreatesSystem, CreatesTechnicalBulletin;
    use CreatesUser;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function storeInput($element, $text)
    {
        $this->assertFilterProducesResults($element);
        $element = str_replace('#', '', $element);
        $element = str_replace("[]", "", $element);
        $this->inputs[$element] = $text;
        return $this;
    }
}
