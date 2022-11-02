<?php

namespace Pimeo\Jobs\Pim\System;

use Pimeo\Events\Pim\SystemWasUpdated;
use Pimeo\Jobs\Job;
use Pimeo\Models\Layer;
use Pimeo\Models\LayerGroup;
use Pimeo\Models\System;
use Pimeo\Repositories\ParentProductRepository;

class CreateSystemLayer extends Job
{
    /** @var array */
    private $fields;

    /** @var LayerGroup */
    private $layer_group;

    /**
     * Create a new job instance.
     *
     * @param array $fields
     * @param LayerGroup $layer_group
     */
    public function __construct(array $fields, LayerGroup $layer_group)
    {
        $this->fields = $fields;
        $this->layer_group = $layer_group;
    }

    /**
     * Execute the job.
     *
     * @param ParentProductRepository $parents
     */
    public function handle(ParentProductRepository $parents)
    {
        $languages = get_current_company_languages();
        $systemLayer = new Layer([
            'layer_group_id' => $this->layer_group->id,
            'position'       => $this->fields['position'],
        ]);

        $product_name = [];
        $product_function = [];
        foreach ($languages as $language) {
            if ($this->fields['type_layer'] == 'parent') {
                $systemLayer->parent_product_id = $this->fields['parent_product'];
                $product = $parents->findWithFields($systemLayer->parent_product_id, ['name', 'product_role']);

                $this->fields['nom_' . $language->code] = $product['name'][$language->code];
                $this->fields['fonction_' . $language->code] = '';

                if (isset($product['product_role'][$language->code])) {
                    $this->fields['fonction_' . $language->code] = $product['product_role'][$language->code];
                }
            }

            $product_name[$language->code] = $this->fields['nom_' . $language->code];
            $product_function[$language->code] = $this->fields['fonction_' . $language->code];
        }

        $systemLayer->product_name = $product_name;
        $systemLayer->product_function = $product_function;
        $systemLayer->save();

        $updatedSystem = $this->updateSystemStatus();

        event(new SystemWasUpdated($updatedSystem));
    }

    private function updateSystemStatus()
    {
        $updateSystem = app(UpdateSystem::class, [$this->layer_group->system, []]);
        return $updateSystem->updateStatus($this->layer_group->system);
    }
}
