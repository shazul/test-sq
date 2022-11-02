<?php

namespace Pimeo\Jobs\Pim\TechnicalBulletin;

use Pimeo\Events\Pim\TechnicalBulletinWasCreated;
use Pimeo\Jobs\Pim\Product\Create;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Repositories\TechnicalBulletinRepository;

class CreateTechnicalBulletin extends Create
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
     * @param TechnicalBulletinRepository $repository
     * @return void
     */
    public function handle(TechnicalBulletinRepository $repository)
    {
        $this->product = $repository->create([
            'company_id'     => current_company()->id,
            'created_by'     => auth()->user()->id,
            'updated_by'     => auth()->user()->id,
            'status'         => AttributableModelStatus::COMPLETE_STATUS,
        ]);

        $this->addAttributes($this->fields['attributes']);
        $this->addMedia($this->fields['media']);

        event(new TechnicalBulletinWasCreated($this->product->fresh()));
    }
}
