<?php

namespace Pimeo\Jobs\Pim\ParentProduct;

use Pimeo\Events\Pim\ParentProductWasCreated;
use Pimeo\Jobs\Pim\Product\Create;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Repositories\ParentProductRepository;

class CreateParentProduct extends Create
{
    use ParentProductTrait;

    private $publish;

    /**
     * Create a new job instance.
     *
     * @param array $fields
     */
    public function __construct(array $fields = [], $publish = false)
    {
        $this->fields = $fields;
        $this->setDefaultValues(['media', 'child_products']);
        $this->publish = $publish;
    }

    /**
     * Execute the job.
     *
     * @param ParentProductRepository $repository
     * @return void
     */
    public function handle(ParentProductRepository $repository)
    {
        $this->product = $repository->create([
            'company_id'     => current_company()->id,
            'nature_id'      => $this->fields['nature_id'],
        ]);

        $this->addAttributes($this->fields['attributes']);
        $this->addMedia($this->fields['media']);
        $this->addChildProducts($this->fields['child_products']);
        $this->setStatus();
        $this->setNewAndStarProduct();

        $this->product->save();

        event(new ParentProductWasCreated($this->product->fresh()));

        return $this->product;
    }

    /**
     * Add child product to the parent one.
     *
     * @param array $child_ids
     */
    public function addChildProducts($child_ids = [])
    {
        $childs = ChildProduct::whereParentProductId($this->product->id)->update(['parent_product_id' => null]);

        if (!empty($child_ids) && is_array($child_ids)) {
            $childProducts = ChildProduct::whereIn('id', $child_ids)->get();

            $this->product->childProducts()->saveMany($childProducts);
        }
    }
}
