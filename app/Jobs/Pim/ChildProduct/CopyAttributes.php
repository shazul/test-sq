<?php

namespace Pimeo\Jobs\Pim\ChildProduct;

use Pimeo\Jobs\Job;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\LinkAttribute;
use Pimeo\Repositories\ChildProductRepository;

class CopyAttributes extends Job
{
    /**
     * @var ChildProduct
     */
    protected $childProduct;

    /**
     * @var int
     */
    protected $copyFromId;

    /**
     * @var ChildProductRepository
     */
    protected $repository;

    /**
     * Create a new job instance.
     *
     * @param ChildProduct           $childProduct
     * @param int                    $copyFromId
     * @param ChildProductRepository $repository
     */
    public function __construct(ChildProduct $childProduct, $copyFromId, ChildProductRepository $repository)
    {
        $this->childProduct = $childProduct;
        $this->copyFromId = $copyFromId;
        $this->repository = $repository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->childProduct->linkAttributes()->with('attribute')->whereHas('attribute', function ($query) {
            $query->whereNotIn('name', ['child_product_name', 'child_product_code']);
        })->delete();

        $copyFrom = $this->repository->find($this->copyFromId);

        /** @var LinkAttribute[] $linkAttributes */
        $linkAttributes = $copyFrom
            ->linkAttributes()
            ->with('attribute')
            ->whereHas('attribute', function ($query) {
                $query->whereNotIn('name', ['child_product_name', 'child_product_code']);
            })->get();

        foreach ($linkAttributes as $linkAttribute) {
            /** @var LinkAttribute $newValues */
            $newValues = $linkAttribute->replicate();
            $newValues->attributable_id = $this->childProduct->id;
            $newValues->save();
        }
    }
}
