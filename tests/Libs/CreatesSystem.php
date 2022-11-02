<?php

namespace Tests\Libs;

use Carbon\Carbon;
use Pimeo\Events\Pim\SystemWasCreated;
use Pimeo\Events\Pim\SystemWasUpdated;
use Pimeo\Jobs\Pim\System\CreateSystem;
use Pimeo\Jobs\Pim\System\UpdateSystem;
use Pimeo\Models\Attribute;
use Pimeo\Models\Layer;
use Pimeo\Models\LayerGroup;
use Pimeo\Models\System;
use Pimeo\Repositories\SystemRepository;

trait CreatesSystem
{
    /**
     * @param bool $withLayers
     *
     * @return mixed
     */
    public function createValidSystem($withLayers = false)
    {
        $data = $this->getRequiredAttributeForCreation(Attribute::MODEL_TYPE_SYSTEM);

        $create_request = new CreateSystem($data);
        $this->expectsEvents(SystemWasCreated::class);
        $create_request->handle(new SystemRepository());

        /** @var System $system */
        $system = System::all()->last();
        if($withLayers !== false){
            $this->buildLayerAndLayerGroupForSystem($system);
        }

        return $system->id;
    }

    /**
     * @param System $system
     * @return System
     */
    public function editValidSystem(System $system)
    {
        $data = $this->getModelAttributeForUpdate($system);

        Carbon::setTestNow(Carbon::now()->addMinute());
        $update_request = new UpdateSystem($system, $data);
        $this->expectsEvents(SystemWasUpdated::class);
        $update_request->handle();

        $updated_system = System::all()->last();

        return $updated_system;
    }

    private function buildLayerAndLayerGroupForSystem(System $system)
    {
        $company = $system->company;
        $languages = $company->languages;

        $name = [];
        foreach ($languages as $language) {
            $name[$language->code] = 'test-name';
        }

        $data = [
            'name' => $name,
            'system_id' => $system->id,
            'position' => 1
        ];
        $layerGroup = LayerGroup::create($data);

        $data = [
            'layer_group_id' => $layerGroup->id,
            'product_name' => $name,
            'product_function' => $name,
            'position' => 1
        ];
        $layer = Layer::create($data);
    }
}
