<?php

namespace Pimeo\Jobs\Pim\Attribute;

use Pimeo\Events\Pim\AttributeWasDeleted;
use Pimeo\Jobs\Job;
use Pimeo\Models\Attribute;

class DeleteAttribute extends Job
{
    /**
     * The attribute to delete.
     *
     * @var Attribute
     */
    protected $attribute;

    /**
     * Create a new job instance.
     *
     * @param Attribute $attribute
     */
    public function __construct(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->attribute->delete();

        event(new AttributeWasDeleted($this->attribute));
    }
}
