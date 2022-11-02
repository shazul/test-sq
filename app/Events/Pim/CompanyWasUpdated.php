<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Events\Event;
use Pimeo\Models\Company;

class CompanyWasUpdated extends Event
{
    use SerializesModels;

    public $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }
}
