<?php

namespace Pimeo\Jobs\Pim\Detail;

use Pimeo\Events\Pim\DetailWasCreated;
use Pimeo\Jobs\Pim\Product\Create;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Company;
use Pimeo\Repositories\DetailRepository;

class CreateDetail extends Create
{
    /**
     * Create a new job instance.
     *
     * @param array $fields
     */
    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
        $this->setDefaultValues(['media']);
    }

    /**
     * Execute the job.
     *
     * @param DetailRepository $repository
     * @return void
     */
    public function handle(DetailRepository $repository)
    {
        $this->product = $repository->create([
            'company_id'     => current_company()->id,
            'status'         => AttributableModelStatus::COMPLETE_STATUS,
        ]);

        $this->addAttributes($this->fields['attributes']);
        $this->addMedia($this->fields['media']);

        event(new DetailWasCreated($this->product->fresh()));
    }
}
