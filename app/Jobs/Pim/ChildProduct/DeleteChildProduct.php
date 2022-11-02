<?php

namespace Pimeo\Jobs\Pim\ChildProduct;

use Pimeo\Events\Pim\ChildProductWasDeleted;
use Pimeo\Jobs\Job;
use Pimeo\Models\ChildProduct;

class DeleteChildProduct extends Job
{
    /**
     * @var ChildProduct
     */
    private $product;

    /**
     * Create a new job instance.
     *
     * @param $product
     */
    public function __construct(ChildProduct $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = $this->product->parentProduct;

        $this->product->linkAttributes()->delete();
        $this->product->mediaLinks()->delete();
        $this->product->delete();

        event(new ChildProductWasDeleted($product));
    }
}
