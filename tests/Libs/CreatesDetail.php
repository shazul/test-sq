<?php

namespace Tests\Libs;

use Carbon\Carbon;
use Pimeo\Events\Pim\DetailWasCreated;
use Pimeo\Events\Pim\DetailWasUpdated;
use Pimeo\Jobs\Pim\Detail\CreateDetail;
use Pimeo\Jobs\Pim\Detail\UpdateDetail;
use Pimeo\Models\Attribute;
use Pimeo\Models\Detail;
use Pimeo\Models\Language;
use Pimeo\Repositories\DetailRepository;

trait CreatesDetail
{
    /**
     * @return mixed
     */
    public function createValidDetail()
    {
        $data = $this->getRequiredAttributeForCreation(Attribute::MODEL_TYPE_DETAIL);

        $create_request = new CreateDetail($data);
        $this->expectsEvents(DetailWasCreated::class);
        $create_request->handle(app(DetailRepository::class));

        return Detail::all()->last()->id;
    }

    /**
     * @param Detail $detail
     * @return Detail
     */
    public function editValidDetail(Detail $detail)
    {
        $data = $this->getModelAttributeForUpdate($detail);

        Carbon::setTestNow(Carbon::now()->addMinute());
        $update_request = new UpdateDetail($detail, $data);
        $this->expectsEvents(DetailWasUpdated::class);
        $update_request->handle(app(DetailRepository::class));

        $updated_detail = Detail::all()->last();

        return $updated_detail;
    }
}
