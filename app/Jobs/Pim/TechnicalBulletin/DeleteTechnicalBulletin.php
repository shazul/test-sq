<?php

namespace Pimeo\Jobs\Pim\TechnicalBulletin;

use Pimeo\Events\Pim\TechnicalBulletinWasDeleted;
use Pimeo\Jobs\Job;
use Pimeo\Models\TechnicalBulletin;

class DeleteTechnicalBulletin extends Job
{
    /**
     * The bulletin to delete.
     *
     * @var TechnicalBulletin
     */
    protected $technical_bulletin;

    /**
     * Create a new job instance.
     *
     * @param TechnicalBulletin $technical_bulletin
     */
    public function __construct(TechnicalBulletin $technical_bulletin)
    {
        $this->technical_bulletin = $technical_bulletin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->technical_bulletin->id;
        $company = $this->technical_bulletin->company;
        $languages = $company->languages;

        $this->technical_bulletin->mediaLinks()->delete();
        $this->technical_bulletin->linkAttributes()->delete();
        $this->technical_bulletin->delete();

        event(new TechnicalBulletinWasDeleted($id, $languages, $company));
    }
}
