<?php

namespace Pimeo\Jobs\Pim\Detail;

use Pimeo\Events\Pim\DetailWasUpdated;
use Pimeo\Jobs\Pim\Product\Update;
use Pimeo\Models\Detail;

class UpdateDetail extends Update
{
    /**
     * @var Detail
     */
    protected $detail;

    /**
     * Create a new job instance.
     *
     * @param Detail $detail
     * @param array  $fields
     */
    public function __construct(Detail $detail, array $fields)
    {
        $this->detail = $detail;
        $this->fields = $fields;
        $this->setDefaultValues(['media']);
        $this->updateStatus($this->detail);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->updateAttributes($this->detail, $this->fields['attributes']);
        $this->updateMedia($this->detail, $this->fields['media']);

        event(new DetailWasUpdated($this->detail->fresh()));
    }

    protected function addMissingFields($attributes)
    {
        // Ajout des min required fields manquants
        $createDetail = app(CreateDetail::class);
        $createDetail->product = $this->detail;
        $createDetail->addAttributes($attributes);
    }
}
