<?php

namespace Pimeo\Jobs\Pim\Detail;

use Pimeo\Events\Pim\DetailWasDeleted;
use Pimeo\Jobs\Job;
use Pimeo\Models\Detail;

class DeleteDetail extends Job
{
    /**
     * The detail to delete.
     *
     * @var Detail
     */
    protected $detail;

    /**
     * Create a new job instance.
     *
     * @param Detail $detail
     */
    public function __construct(Detail $detail)
    {
        $this->detail = $detail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->detail->id;
        $company = $this->detail->company;
        $languages = $company->languages;

        $this->detail->linkAttributes()->delete();
        $this->detail->mediaLinks()->delete();
        $this->detail->delete();

        event(new DetailWasDeleted($id, $languages, $company));
    }
}
