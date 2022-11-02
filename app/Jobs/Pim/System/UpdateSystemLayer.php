<?php

namespace Pimeo\Jobs\Pim\System;

use Pimeo\Events\Pim\SystemWasUpdated;
use Pimeo\Jobs\Job;
use Pimeo\Models\Layer;
use Pimeo\Repositories\ParentProductRepository;

class UpdateSystemLayer extends Job
{
    /** @var array */
    private $fields;

    /** @var Layer */
    private $layer;

    /**
     * Create a new job instance.
     *
     * @param array $fields
     * @param Layer $layer
     */
    public function __construct(array $fields, Layer $layer)
    {
        $this->fields = $fields;
        $this->layer = $layer;
    }

    /**
     * Execute the job.
     *
     * @param ParentProductRepository $parents
     */
    public function handle(ParentProductRepository $parents)
    {
        $systemLayer = $this->layer;
        $languages = get_current_company_languages();

        if ($this->fields['type_layer'] == 'parent') {
            $systemLayer->parent_product_id = $this->fields['parent_product'];
            $product = $parents->findWithFields($systemLayer->parent_product_id, ['name', 'product_role']);

            foreach ($languages as $language) {
                $this->fields['nom_' . $language->code] = $product['name'][$language->code];
                if (isset($product['product_role'][$language->code])) {
                    $this->fields['fonction_' . $language->code] = $product['product_role'][$language->code];
                } else {
                    $this->fields['fonction_' . $language->code] = '';
                }
            }
        } else { // Substrat
            $systemLayer->parent_product_id = null;
        }

        $product_name = [];
        $product_function = [];

        foreach ($languages as $language) {
            $product_name[$language->code] = $this->fields['nom_' . $language->code];
            $product_function[$language->code] = $this->fields['fonction_' . $language->code];
        }

        $systemLayer->product_name = $product_name;
        $systemLayer->product_function = $product_function;

        $systemLayer->position = $this->fields['position'];

        $systemLayer->save();

        event(new SystemWasUpdated($systemLayer->layerGroup->system));
    }
}
