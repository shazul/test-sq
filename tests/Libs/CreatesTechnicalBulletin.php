<?php

namespace Tests\Libs;

use Carbon\Carbon;
use Pimeo\Events\Pim\TechnicalBulletinWasCreated;
use Pimeo\Events\Pim\TechnicalBulletinWasUpdated;
use Pimeo\Jobs\Pim\TechnicalBulletin\CreateTechnicalBulletin;
use Pimeo\Jobs\Pim\TechnicalBulletin\UpdateTechnicalBulletin;
use Pimeo\Models\Attribute;
use Pimeo\Models\Language;
use Pimeo\Models\TechnicalBulletin;
use Pimeo\Repositories\TechnicalBulletinRepository;

trait CreatesTechnicalBulletin
{
    /**
     * @return mixed
     */
    public function createValidTechnicalBulletin()
    {
        $data = $this->getRequiredAttributeForCreation(Attribute::MODEL_TYPE_TECHNICAL_BULLETIN);

        $create_request = new CreateTechnicalBulletin($data);
        $this->expectsEvents(TechnicalBulletinWasCreated::class);
        $create_request->handle(new TechnicalBulletinRepository());

        return TechnicalBulletin::all()->last()->id;
    }

    /**
     * @param TechnicalBulletin $technical_bulletin
     * @return TechnicalBulletin
     */
    public function editValidTechnicalBulletin(TechnicalBulletin $technical_bulletin)
    {
        $data = $this->getModelAttributeForUpdate($technical_bulletin);

        Carbon::setTestNow(Carbon::now()->addMinute());
        $update_request = new UpdateTechnicalBulletin($technical_bulletin, $data);
        $this->expectsEvents(TechnicalBulletinWasUpdated::class);
        $update_request->handle();

        $updated_technical_bulletin = TechnicalBulletin::all()->last();

        return $updated_technical_bulletin;
    }
}
