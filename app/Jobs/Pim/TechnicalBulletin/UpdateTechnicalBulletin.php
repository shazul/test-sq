<?php

namespace Pimeo\Jobs\Pim\TechnicalBulletin;

use Pimeo\Events\Pim\TechnicalBulletinWasUpdated;
use Pimeo\Jobs\Pim\Product\Update;
use Pimeo\Models\TechnicalBulletin;

class UpdateTechnicalBulletin extends Update
{
    /**
     * @var TechnicalBulletin
     */
    protected $technical_bulletin;

    /**
     * Create a new job instance.
     *
     * @param TechnicalBulletin $technical_bulletin
     * @param array $fields
     */
    public function __construct(TechnicalBulletin $technical_bulletin, array $fields)
    {
        $this->technical_bulletin = $technical_bulletin;
        $this->fields = $fields;
        $this->setDefaultValues(['media']);
        $this->updateStatus($this->technical_bulletin);
    }

    public function handle()
    {
        $this->updateAttributes($this->technical_bulletin, $this->fields['attributes']);
        $this->updateMedia($this->technical_bulletin, $this->fields['media']);

        event(new TechnicalBulletinWasUpdated($this->technical_bulletin->fresh()));
    }

    protected function addMissingFields($attributes)
    {
        // Ajout des min required fields manquants
        $createTechnicalBulletin = app(CreateTechnicalBulletin::class);
        $createTechnicalBulletin->product = $this->technical_bulletin;
        $createTechnicalBulletin->addAttributes($attributes);
    }
}
