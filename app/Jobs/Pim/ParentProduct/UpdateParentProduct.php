<?php

namespace Pimeo\Jobs\Pim\ParentProduct;

use Pimeo\Events\Pim\ParentProductWasUpdated;
use Pimeo\Jobs\Pim\Product\Update;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Layer;
use Pimeo\Models\ParentProduct;
use Pimeo\Repositories\ParentProductRepository;

class UpdateParentProduct extends Update
{
    use ParentProductTrait;

    /**
     * @var ParentProduct
     */
    protected $product;

    /** @var ParentProductRepository */
    private $parentRepo;

    /**
     * Create a new job instance.
     *
     * @param ParentProduct $product
     * @param array         $fields
     */
    public function __construct(ParentProduct $product, array $fields, $publish = false)
    {
        $this->product = $product;
        $this->fields = $fields;
        $this->setDefaultValues(['media', 'child_products']);
        $this->publish = $publish;
    }

    /**
     * Execute the job.
     * @param ParentProductRepository $parentRepo
     */
    public function handle(ParentProductRepository $parentRepo)
    {
        $this->parentRepo = $parentRepo;

        $this->updateAttributes($this->product, $this->fields['attributes']);
        $this->updateMedia($this->product, $this->fields['media']);
        //$this->updateChildProducts($this->fields['child_products']);
        $this->setStatus();
        $this->setNewAndStarProduct();

        $this->product->save();

        $this->updateAssociatedSystemLayers();

        event(new ParentProductWasUpdated($this->product->fresh()));
    }

    public function addMissingFields($attributes)
    {
        // Ajout des min required fields manquants
        $createParentProduct = app(CreateParentProduct::class);
        $createParentProduct->product = $this->product;
        $createParentProduct->addAttributes($attributes);
    }

    /**
     * Update child products in parent product.
     *
     * @param array $child_ids
     */
    protected function updateChildProducts($child_ids = [])
    {
        ChildProduct::whereParentProductId($this->product->id)->update(['parent_product_id' => null]);

        if (!empty($child_ids) && is_array($child_ids)) {
            $childProducts = ChildProduct::whereIn('id', $child_ids)->get();

            $this->product->childProducts()->saveMany($childProducts);
        }
    }

    private function updateAssociatedSystemLayers()
    {
        $product = $this->parentRepo->findWithFields($this->product->id, ['name', 'product_role']);

        $layers = Layer::whereParentProductId($this->product->id)->get();
        $languages = get_current_company_languages();
        foreach ($layers as $layer) {
            $product_name = [];
            $product_function = [];
            foreach ($languages as $language) {
                $role = '';
                if (isset($product['product_role'][$language->code])) {
                    $role = $product['product_role'][$language->code];
                }

                $product_name[$language->code] = $product['name'][$language->code];
                $product_function[$language->code] = $role;
            }

            $layer->product_name = $product_name;
            $layer->product_function = $product_function;

            $layer->save();
        }
    }
}
