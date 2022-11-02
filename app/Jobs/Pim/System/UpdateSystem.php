<?php

namespace Pimeo\Jobs\Pim\System;

use Pimeo\Events\Pim\SystemWasUpdated;
use Pimeo\Jobs\Pim\Product\Update;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\System;

class UpdateSystem extends Update
{
    /**
     * @var System
     */
    protected $system;

    /**
     * Create a new job instance.
     *
     * @param System $system
     * @param array $fields
     */
    public function __construct(System $system, array $fields)
    {
        $this->system = $system;
        $this->fields = $fields;
        $this->setDefaultValues(['media']);
        $this->updateStatus($this->system);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->system->buildingComponents()->sync($this->fields['buildingComponents']);
        $this->updateAttributes($this->system, $this->fields['attributes']);
        $this->updateMedia($this->system, $this->fields['media']);

        event(new SystemWasUpdated($this->system->fresh()));
    }

    public function addMissingFields($attributes)
    {
        // Ajout des min required fields manquants
        $createSystem = app(CreateSystem::class);
        $createSystem->product = $this->system;
        $createSystem->addAttributes($attributes);
    }

    public function updateStatus($system)
    {
        $system->status = AttributableModelStatus::INCOMPLETE_STATUS;

        $nbLayerGroups = $system->layerGroups->count();
        if ($nbLayerGroups > 0) {
            $nbLayers = $system->layerGroups[0]->layers->count();
            if ($nbLayers > 0) {
                $system->status = AttributableModelStatus::COMPLETE_STATUS;
            }
        }

        $system->save();

        return $system;
    }
}
